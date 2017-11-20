<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\MaxMD\Message\Contracts\IFolder;
use Endeavors\Support\VO\ModernString;
use Endeavors\Support\VO\ModernArray;
use Endeavors\MaxMD\Support\Client;

class Folder implements IFolder
{
    use Traits\RequestValidator;

    protected $response;

    protected $folder;

    private function __construct($folder)
    {
        // modern string will ensure we have a string
        $modernString = ModernString::create($folder);

        if( $modernString->isEmpty() ) {
            throw new Exceptions\InvalidFolderException("The folder name must have length");
        }

        $this->folder = $folder;
    }

    public static function create($folder)
    {
        return new static($folder);
    }

    public function Children()
    {
        $request = [
            "auth" => $this->user(),
            "rootFolderName" => "INBOX." . $this->get(),
            "subscribedFolderOnly" => "false"
        ];

        $this->response = Client::DirectMessage()->GetFolders($request);

        return $this;
    }

    public function MoveMessages($uids, IFolder $folder)
    {
        $fromFolder = $this->get();
        
        if( ModernString::create($fromFolder)->toLower() !== "inbox" && false === ModernString::create($fromFolder)->toLower()->position("inbox") ) {
            // if we do not have the inbox and we do not have a prefix
            $fromFolder = "Inbox." . $fromFolder;
        }

        $toFolder = $folder->get();

        if( ModernString::create($toFolder)->toLower() !== "inbox" && false === ModernString::create($toFolder)->toLower()->position("inbox") ) {
            // if we do not have the inbox and we do not have a prefix
            $toFolder = "Inbox." . $toFolder;
        }
        
        $request = [
            "auth" => $this->user(),
            "folderName" => $fromFolder,
            "destFolderName" => $toFolder,
            "uids" => $uids
        ];
        
        $this->response = Client::DirectMessage()->MoveMessagesByUID($request);

        return $this;
    }
    
    /**
     * @throws Exceptions\InvalidUserException
     */
    public function Make()
    {
        $this->validateReservedFolders();

        $request = [
            "auth" => $this->user(),
            "folderName" => "INBOX." . $this->get()
        ];

        $this->response = Client::DirectMessage()->CreateFolder($request);

        return $this;
    }
    
    /**
     * @param closure - allow the developer to perform actions before everything is deleted
     * @throws Exceptions\InvalidUserException
     */
    public function Delete(\Closure $callBack = null)
    {
        $this->validateReservedFolders();

        $that = $this;

        if( null !== $callBack ) {
            $callBack($that);
        }
        
        $request = [
            "auth" => $this->user(),
            "folderName" => "INBOX." . $this->get()
        ];
        
        $this->response = Client::DirectMessage()->DeleteFolder($request);

        return $this;
    }

    public function Rename(IFolder $folder)
    {   
        $this->validateReservedFolders();

        $request = [
            "auth" => $this->user(),
            "folderName" => "INBOX." . $this->get(),
            "newFolderName" => "INBOX." . $folder->get()
        ];
        
        $this->response = Client::DirectMessage()->MoveFolder($request);

        return $this;
    }
    
    /**
     * for now prefix with "INBOX" to see messages of another folder
     */
    public function Messages()
    {   
        $folder = strtolower($this->get());

        $fqFolderName = explode('.', $folder);

        $names = [];

        foreach($fqFolderName as $fqName) {
            $names[] = ucfirst($fqName);
        }

        $folder = implode('.', $names);

        $request = [
            "auth" => $this->user(),
            "folderName" => $folder
        ];
        
        $this->response = Client::DirectMessage()->GetMessages($request);

        return Messages::create($this->ToObject());
    }

    public function UnreadMessages()
    {
        $request = [
            "auth" => $this->user(),
            "folderName" => $this->get()
        ];
        
        $this->response = Client::DirectMessage()->GetUnreadMessages($request);
        
        return Messages::create($this->ToObject());
    }

    public function UnreadMessageCount()
    {
        $request = [
            "auth" => $this->user(),
            "folderName" => $this->get()
        ];
        
        $this->response = Client::DirectMessage()->GetUnreadMessageCount($request);

        $result = $this->ToObject();

        if( ! property_exists($result, 'count') ) {
            return 0;
        }
        
        return $this->ToObject()->count;
    }

    public function ToObject()
    {
        if( null === $this->Raw() ) {
            return json_decode(json_encode([
                'success' => false
            ]));
        }
        
        return $this->Raw()->return;
    }

    public function Raw()
    {
        return $this->response;
    }

    public function get()
    {
        return $this->folder;
    }

    public function __toString()
    {
        return $this->get();
    }

    final protected function user()
    {
        $user = User::getInstance();

        return $user->ToArray();
    }

    final protected function reserved()
    {
        return ModernArray::create([
            'Sent',
            'Templates',
            'Drafts',
            'Spam'
        ]);
    }
}