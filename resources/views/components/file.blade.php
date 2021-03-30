
<ul>
    <li>Nombre : {{$file->getName()}}</li>
    <li>Tipo : {{$file->getMimeType()}}</li>
    <li>Id : {{$file->getId()}}</li>
    <li>Hijos : </li>
    <ul>
        @foreach ($file->children as $children )
        <x-file :file="$children" />
        @endforeach
    </ul>
    <li>Permisos : </li>
    <ul>
        @foreach ($file->permissions as $permission )
        <li>{{$permission->getEmailAddress()}} </li>
        @endforeach
    </ul>

</ul>
