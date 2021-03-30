<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{

    public function __construct(Google_Client $client)
    {
        $this->middleware(function($request , $next) use ($client) {
            $client->refreshToken(Auth::user()->refresh_token);
            $this->drive = new Google_Service_Drive($client);
            return $next($request);
        });
    }

    public function index() {

        $this->listFolders('root');
    }

    private function listFolders($id) {

        $files = [];

        $files = $this->getFiles($id);


        print view('list' , compact('files'));
    }

    private function getFiles( $id ) {

        $files = [];

        $query = "'" . $id . "' in parents and trashed=false";

        $optParams = [
            'fields' => 'files(id , name , mimeType , properties)',
            'q' => $query
        ];

        $results = $this->drive->files->listFiles($optParams);

        foreach ($results as $result) {
            $files[$result->id] = $result;
            $files[$result->id]->children = [];
            $files[$result->id]->permissions = $this->drive->permissions->listPermissions($result->id , ['fields' => 'permissions(id , emailAddress)']);
            if($result->getMimeType() == 'application/vnd.google-apps.folder'){
                $files[$result->id]->children = $this->getFiles($result->id);
            }
        }

        return $files;

    }

}
