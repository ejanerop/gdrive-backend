<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(Google_Client $client)
    {
        $this->middleware(function($request , $next) use ($client) {
            $client->refreshToken($request->input('api_token'));
            $this->drive = new Google_Service_Drive($client);
            return $next($request);
        });
    }

    public function index( Request $request ) {

        $files = [];

        $email = $request->input('email') ? $request->input('email') : null;

        $files = $this->getFiles('root' , $email);


        return ['files' => $files , 'email' => $email];
    }

    public function removePermission( Request $request ) {

        if($request->input('files') && $request->input('permission')) {

            $permission = $request->input('permission');

            foreach ($request->input('files') as $file) {
                $this->drive->permissions->delete($file , $permission);
            }
        }

        return response()->json('Correcto' , 200);

    }


    private function getFiles( $id, $email ) {
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
                $files[$result->id]->children = $this->getFiles($result->id , $email);
            }
        }


        if ($email != '') {
            $files = $this->filterByEmail( $files , $email );
        }
        return $files;
    }

    private function filterByEmail( $files ,  string $email ) {

        $result = [];
        $add = false;

        foreach ($files as $file) {
            $add = false;
            foreach ($file->permissions as $permission) {
                if($permission->getEmailAddress() == $email){
                    $add = true;
                }
            }
            if ($add) {
                $result[$file->id] = $file;
            }
        }
        return $result;

    }
}
