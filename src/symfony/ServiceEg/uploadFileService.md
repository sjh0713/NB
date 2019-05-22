## 文件上传服务

    <?php
    namespace BaseBundle\Service;
    
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Filesystem\Filesystem;
    use Psr\Log\LoggerInterface;
    
    class UploadFileService
    {
    
        private $targetDir;
        private $fileSystem;
        private $logger;
        private $image;
    
        public function __construct($targetDir, Filesystem $fileSystem, LoggerInterface $logger, ImageService $image)
        {
            $this->targetDir = $targetDir;
            $this->fileSystem = $fileSystem;
            $this->logger = $logger;
            $this->image = $image;
        }
    
        public function upload(UploadedFile $file, $subDir = '')
        {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $subDir = empty($subDir) ? '' : '/' . $subDir;
            try {
                $file->move($this->targetDir . $subDir, $fileName);
                $file->getPath();
            } catch (\Exception $e) {
                return [
                    'code' => 0,
                    'data' => $e->getCode() . $e->getMessage()
                ];
            }
            return [
                'code' => 1,
                'data' => 'uploads' . $subDir . '/' . $fileName
            ];
        }
    
        /**
         * 文件上传升级版本
         * @param UploadedFile $file
         * @param array $mimeTypes 上传的文件允许的MIME，示例：图片['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png']
         * @param string $subDir
         * @return number[]|string[]
         */
        public function run(UploadedFile $file,  $mimeTypes, $subDir = '', $text = '')
        {
            $errorCode = $file->getError();
            switch ($errorCode) {
                case 0:
                    // 正常上传
                    break;
                case 1:
                    return [
                        'code' => 2,
                        'msg' => '上传的文件超过了配置文件的最大值'
                    ];
                    break;
                case 2:
                    return [
                        'code' => 3,
                        'msg' => '上传文件的大小超过了表单的最大值'
                    ];
                    break;
                case 3:
                    return [
                        'code' => 4,
                        'msg' => '文件只有部分被上传'
                    ];
                    break;
                case 4:
                    return [
                        'code' => 5,
                        'msg' => '没有文件被上传'
                    ];
                    break;
                case 6:
                    return [
                        'code' => 6,
                        'msg' => '找不到临时文件夹'
                    ];
                    break;
                case 7:
                    return [
                        'code' => 7,
                        'msg' => '文件写入失败'
                    ];
                    break;
                default:
                    return [
                        'code' => 8,
                        'msg' => '文件上传失败，未知错误'
                    ];
                    break;
            }
            $fileSuffix = $file->getClientOriginalExtension();
            $fileMime = $file->getMimeType();
            $this->logger->emergency($fileMime);
            if(!in_array($fileMime, $mimeTypes)){
                return [
                    'code' => 9,
                    'msg' => '文件格式错误,MIME校验失败'
                ];
            }
            $fileName = md5(uniqid()) . '.' . $fileSuffix;
            $subDir = empty($subDir) ? '' : '/' . $subDir;
            try {
                if(!is_dir('uploads' . $subDir)){
                    $this->fileSystem->mkdir('uploads' . $subDir);
                }
                $file->move($this->targetDir . $subDir, $fileName);
                $file->getPath();
                if(!empty($text)){
                    $filePath = 'uploads' . $subDir . '/' . $fileName;
                    $result = $this->image->run($filePath, $text, true);
                    if($result['code'] == 1){
                        return [
                            'code' => 1,
                            'msg' => '上传成功',
                            'data' => $filePath . '.jpeg'
                        ];
                    }else{
                        return $result;
                    }
                }else{
                    return [
                        'code' => 1,
                        'msg' => '上传成功',
                        'data' => 'uploads' . $subDir . '/' . $fileName
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'code' => 10,
                    // 'data' => '上传失败,服务端异常'
                    'msg' => $e->getCode() . $e->getMessage()
                ];
            }
        }
    }

## 调用方式
    $path = $request->files->get('file');
    /**
     *
     * @var \BaseBundle\Service\UploadFileService $uploadService
     */
    $uploadService = $this->get('base.upload_file_service');
    if (! ($path instanceof UploadedFile)) {
        return $this->json([
            'code' => 500,
            'msg' => '图片信息不能为空'
        ]);
    }
    //需要传入2个参数，第三个参数是进行打水印
    $imageResult = $uploadService->run($path, [
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png'
    ], 'image/attendance', $position);
