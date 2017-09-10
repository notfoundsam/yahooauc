<?php

/**
* 
*/
class Helper
{
    public static function getS3Client()
    {
        return new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => \Config::get('my.aws.region'),
        ]);
    }

    public static function putImgToS3($fileName = null, $body = null)
    {
        $s3 = self::getS3Client();

        $result = $s3->putObject([
            'Bucket'     => \Config::get('my.aws.bucket'),
            'Key'        => '01/01.jpg',
            'SourceFile' => APPPATH.'/tmp/01.jpg'
        ]);

        // Debug::dump($result);
    }
}
