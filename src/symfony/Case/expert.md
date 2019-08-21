- Excel导出功能
    
    - 在composer.json中require引入"liuggio/excelbundle": "^2.1",
    
    - 在AppKernel.php中$bundles组册：new Liuggio\ExcelBundle\LiuggioExcelBundle(),
    
    - 案例：
        
            /**
             * 专家excel导出
             * @param Request $request
             * @return false|string
             */
            public function excelAction(Request $request)
            {
                $em = $this->getDoctrine()->getManager();
        
                //标题
                $documentName = '专家信息导出';
        
                //表头
                $headlines = ['专家名称', '性别', '政治面貌', '籍贯省', '籍贯市', '单位', '职称', '学历','专业类型','联系电话','出生年月','身份证号','年龄','毕业院校','职务','住址'];
        
                //数据
                /**
                 * @var \ExpertBundle\Repository\ExpertRepository $expertRepo
                 */
                $expertRepo = $em->getRepository('ExpertBundle:Expert');
                $expert_id = $request->request->get('expert_id');
                $data = $expertRepo->excel($expert_id);
                $type = 'xls';
                $result = $this->export($documentName, $headlines, $data, $type);
                $url = '/file/'.$result;
                
                return  $this->json(['url'=>$url]);
        
                //返回文件的资源类型，通常用于a链接
                //$root = $this->get('kernel')->getProjectDir();
                //return $this->file($root . '/web/file/' . $result, $documentName . '.' . $type);
            }
        
            /**
             * @param $documentName
             * @param array $headlines
             * @param array $data
             * @param string $type
             * @return string
             * @throws \PHPExcel_Exception
             * @throws \PHPExcel_Writer_Exception
             * excel导出
             */
            public function export($documentName, array $headlines, array $data, $type = 'xls')
            {
                /**
                 *
                 * @var \Liuggio\ExcelBundle\Factory $phpexcelService
                 */
                $phpexcelService = $this->get('phpexcel');
                $phpExcelObject = $phpexcelService->createPHPExcelObject();
        
                $phpExcelObject->getProperties()
                    ->setCreator("Shanxi technology co., LTD")
                    ->setLastModifiedBy("Shanxi technology co., LTD")
                    ->setTitle("Shanxi Document")
                    ->setSubject("Shanxi Excel")
                    ->setDescription("Power by Shanxi technology co., LTD")
                    ->setKeywords("Excel Shanxi")
                    ->setCategory("Shanxi");
                $sheet = $phpExcelObject->setActiveSheetIndex(0);
                $cellCount = count($headlines);
                $dletters = [
                    'AA',
                    'AB',
                    'AC',
                    'AD',
                    'AE',
                    'AF',
                    'AG',
                    'AH',
                    'AI',
                    'AJ',
                    'AK',
                    'AL',
                    'AM',
                    'AN',
                    'AO',
                    'AP',
                    'AQ',
                    'AR',
                    'AS',
                    'AT',
                    'AU',
                    'AV',
                    'AW',
                    'AX',
                    'AY',
                    'AZ'
                ];
                $letters = array_merge(range('A', 'Z'), $dletters);
                $maxCount = count($letters);
                $cellCount = $cellCount > $maxCount ? $maxCount : $cellCount;
                for ($i = 0; $i < $cellCount; $i++) {
                    $sheet->setCellValue($letters[$i] . '1', isset($headlines[$i]) ? $headlines[$i] : '');
                }
                $a = array();
                foreach ($data as $key => $val) {
                    $b = array();
                    foreach ($val as $k => $v) {
                        $b[] = $v;
                    }
                    $a[] = $b;
                }
                foreach ($a as $key => $val) {
                    foreach ($val as $k => $v) {
                        $sheet->setCellValue($letters[$k] . ($key + 2), $v);
                    }
                }
                $phpExcelObject->getActiveSheet()->setTitle($documentName);
                $writer = $phpexcelService->createWriter($phpExcelObject, 'Excel5');
                $filename = uniqid(date('Y_m_d_H_i_s')) . '.xls';
                $writer->save('file/' . $filename);
                // create the response
                $response = $phpexcelService->createStreamedResponse($writer);
                // adding headers（use Symfony\Component\HttpFoundation\ResponseHeaderBag;）
                $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
                $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
                $response->headers->set('Pragma', 'public');
                $response->headers->set('Cache-Control', 'maxage=1');
                $response->headers->set('Content-Disposition', $dispositionHeader);
                return $filename;
            }
