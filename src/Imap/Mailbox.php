<?php

namespace Endeavors\MaxMD\Message\Imap;

use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
use FilesystemIterator;

class Mailbox extends \PhpImap\Mailbox
{
    /**
	 * Get mail data
	 *
	 * @param $mailId
	 * @param bool $markAsSeen
	 * @return IncomingMail
	 */
	public function getMail($mailId, $markAsSeen = true) {
		$mail = new IncomingMail();
		$mail->setHeader($this->getMailHeader($mailId));

		$mailStructure = $this->imap('fetchstructure', [$mailId, FT_UID]);

		if(empty($mailStructure->parts)) {
			$this->initMailPart($mail, $mailStructure, 0, $markAsSeen);
		}
		else {
            $this->cleanMailAttachmentDirectory($mail);
			foreach($mailStructure->parts as $partNum => $partStructure) {
				$this->initMailPart($mail, $partStructure, $partNum + 1, $markAsSeen);
			}
		}

		return $mail;
    }
    
    protected function initMailPart(IncomingMail $mail, $partStructure, $partNum, $markAsSeen = true) {
		$options = FT_UID;
		if(!$markAsSeen) {
			$options |= FT_PEEK;
		}

		if($partNum) { // don't use ternary operator to optimize memory usage / parsing speed (see http://fabien.potencier.org/the-php-ternary-operator-fast-or-not.html)
			$data = $this->imap('fetchbody', [$mail->id, $partNum, $options]);
		}
		else {
			$data = $this->imap('body', [$mail->id, $options]);
		}

		if($partStructure->encoding == 1) {
			$data = imap_utf8($data);
		}
		elseif($partStructure->encoding == 2) {
			$data = imap_binary($data);
		}
		elseif($partStructure->encoding == 3) {
			$data = preg_replace('~[^a-zA-Z0-9+=/]+~s', '', $data); // https://github.com/barbushin/php-imap/issues/88
			$data = imap_base64($data);
		}
		elseif($partStructure->encoding == 4) {
			$data = quoted_printable_decode($data);
		}

		$params = [];
		if(!empty($partStructure->parameters)) {
			foreach($partStructure->parameters as $param) {
				$params[strtolower($param->attribute)] = $this->decodeMimeStr($param->value);
			}
		}
		if(!empty($partStructure->dparameters)) {
			foreach($partStructure->dparameters as $param) {
				$paramName = strtolower(preg_match('~^(.*?)\*~', $param->attribute, $matches) ? $matches[1] : $param->attribute);
				if(isset($params[$paramName])) {
					$params[$paramName] .= $param->value;
				}
				else {
					$params[$paramName] = $param->value;
				}
			}
		}

		$isAttachment = $partStructure->ifid || isset($params['filename']) || isset($params['name']);

		// ignore contentId on body when mail isn't multipart (https://github.com/barbushin/php-imap/issues/71)
		if(!$partNum && TYPETEXT === $partStructure->type) {
			$isAttachment = false;
		}

		if($isAttachment) {
			$attachmentId = mt_rand() . mt_rand();

			if(empty($params['filename']) && empty($params['name'])) {
				$fileName = $attachmentId . '.' . strtolower($partStructure->subtype);
			}
			else {
				$fileName = !empty($params['filename']) ? $params['filename'] : $params['name'];
				$fileName = $this->decodeMimeStr($fileName, $this->serverEncoding);
				$fileName = $this->decodeRFC2231($fileName, $this->serverEncoding);
			}

			$attachment = new IncomingMailAttachment();
			$attachment->id = $attachmentId;
			$attachment->contentId = $partStructure->ifid ? trim($partStructure->id, " <>") : null;
			$attachment->name = $fileName;
			$attachment->disposition = (isset($partStructure->disposition) ? $partStructure->disposition : null);
			if($this->attachmentsDir) {
				$replace = [
					'/\s/' => '_',
					'/[^0-9a-zа-яіїє_\.]/iu' => '',
					'/_+/' => '_',
					'/(^_)|(_$)/' => '',
				];
                $fileSysName = preg_replace('~[\\\\/]~', '', $mail->id . '_' . $attachmentId . '_' . preg_replace(array_keys($replace), $replace, $fileName));
                
                $this->makeMailAttachmentDirectory($mail);
				$attachment->filePath = $this->attachmentsDir . DIRECTORY_SEPARATOR . $mail->id . DIRECTORY_SEPARATOR . $fileSysName;

				if(strlen($attachment->filePath) > 255) {
					$ext = pathinfo($attachment->filePath, PATHINFO_EXTENSION);
					$attachment->filePath = substr($attachment->filePath, 0, 255 - 1 - strlen($ext)) . "." . $ext;
				}

				file_put_contents($attachment->filePath, $data);
			}
			$mail->addAttachment($attachment);
		}
		else {
			if(!empty($params['charset'])) {
				$data = $this->convertStringEncoding($data, $params['charset'], $this->serverEncoding);
			}
			if($partStructure->type == 0 && $data) {
				if(strtolower($partStructure->subtype) == 'plain') {
					$mail->textPlain .= $data;
				}
				else {
					$mail->textHtml .= $data;
				}
			}
			elseif($partStructure->type == 2 && $data) {
				$mail->textPlain .= trim($data);
			}
		}
		if(!empty($partStructure->parts)) {
			foreach($partStructure->parts as $subPartNum => $subPartStructure) {
				if($partStructure->type == 2 && $partStructure->subtype == 'RFC822' && (!isset($partStructure->disposition) || $partStructure->disposition !== "attachment")) {
					$this->initMailPart($mail, $subPartStructure, $partNum, $markAsSeen);
				}
				else {
					$this->initMailPart($mail, $subPartStructure, $partNum . '.' . ($subPartNum + 1), $markAsSeen);
				}
			}
		}
    }
    
    public function makeMailAttachmentDirectory($mail)
    {
        if( ! is_dir($this->attachmentsDir . DIRECTORY_SEPARATOR . $mail->id) ) {
            return mkdir($this->attachmentsDir . DIRECTORY_SEPARATOR . $mail->id, 0755, true);
        }
    }

    public function cleanMailAttachmentDirectory($mail)
    {
        $this->cleanDirectory($this->attachmentsDir . DIRECTORY_SEPARATOR . $mail->id);
    }

    protected function deleteDirectory($directory, $preserve = false)
    {
        if (! is_dir($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            // If the item is a directory, we can just recurse into the function and
            // delete that sub-directory otherwise we'll just delete the file and
            // keep iterating through each file until the directory is cleaned.
            if ($item->isDir() && ! $item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            }

            // If the item is just a file, we can go ahead and delete it since we're
            // just looping through and waxing all of the files in this directory
            // and calling directories recursively, so we delete the real path.
            else {
                $this->delete($item->getPathname());
            }
        }

        if (! $preserve) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Empty the specified directory of all files and folders.
     *
     * @param  string  $directory
     * @return bool
     */
    protected function cleanDirectory($directory)
    {
        return $this->deleteDirectory($directory, true);
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array  $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (! @unlink($path)) {
                    $success = false;
                }
            } catch (\ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }
}