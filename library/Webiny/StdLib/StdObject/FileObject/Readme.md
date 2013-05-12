FileObject
===========
The FileObject class is a helper class for when working with files.

Example usage:

    use Webiny\StdLib\StdObject\FileObject\FileObject;

    $file = new FileObject('/var/www/file.txt');

    echo $file->getFileContent() // some text

    $file->write('additional text')->touch();