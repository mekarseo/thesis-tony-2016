<?php
/**
 * @Final File
 * 
 * Table user_personal_info
 * @property 	[1] => ชื่อ
 * @property	[2] => นามสกุล
 * @property	[3] => เลขประจำตัวประชาชน
 * @property	[4] => วัน เดือน ปี เกิด
 * @property    [5] => โทรศัพท์มือถือ
 * @property    [6] => E-mail
 * @property    [22] => ชื่อ-นามสกุล บิดา
 * @property    [23] => เบอร์โทรศัพท์บิดา
 * @property    [24] => ชื่อ-นามสกุล มารดา
 * @property    [25] => เบอร์โทรศัพท์มารดา
 * @property    [26] => ชื่อ-นามสกุล ผู้ปกครอง
 * @property    [27] => เบอร์โทรศัพท์ ผู้ปกครอง
 * @property    [28] => สถานที่ติดต่อผู้ปกครอง
 *
 * Table user_education_info
 * @property 	[7] => สถานศึกษา
 * @property   	[8] => จังหวัด
 * @property    [9] => สาขาวิชา
 * @property    [10] => วุฒิการศึกษา
 * @property    [11] => เกรดเฉลี่ยรวม (GPA.)
 * @property    [12] => เข้าศึกษาระดับ
 * @property    [13] => สาขาที่เข้าศึกษา
 * @property    [14] => คณะ/วิทยาลัย
 *
 * Table user_talent_info
 * @property	[15] => โครงการผู้มีความสามารถด้าน
 * @property    [16] => ชนิดกีฬา
 * @property    [17] => ผลงานความสามารถ
 * @property    [18] => ความสามารถด้าน
 * @property    [19] => ประเภท
 * @property    [20] => ชนิด
 * @property    [21] => ผลงานความสามารถ
 */
namespace app\modules\management\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class CsvModel extends Model
{
	public $uploadFile;
	public $term;
	protected $fileName;

	public function rules()
	{
        return [
            [['uploadFile', 'term'], 'required'],
            [['term'], 'string', 'length' => 6],
            [['uploadFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'checkExtensionByMimeType' => false, 'maxSize' => 1024 * 1024 * 5],
        ];
    }
   
    public function attributeLabels()
    {
        return [
            'uploadFile' 	=> Yii::t('app', 'Select CSV File'),
        ];
    }

    public function upload()
	{
		$this->fileName = 'upload_' . date('Ymd_His') . '_' . $this->uploadFile->size . '.' . $this->uploadFile->extension;

		if ($this->validate()) {
			$this->uploadFile->saveAs(Yii::getAlias('@webroot') . '/uploads/' . $this->fileName);
			$this->insert();
			return true;
		}

		return false;
	}

	protected function insert()
	{
		$CsvContent = [];
        $CsvFile = fopen(Yii::getAlias('@webroot') . '/uploads/' . $this->fileName, 'r');
        while(! feof($CsvFile))
        {
            $CsvContent[] = str_replace('เเ', 'แ', fgetcsv($CsvFile));
        }
        fclose($CsvFile);
        unset($CsvContent[0]);

        $duplicate = [];
        foreach ($CsvContent as $content) {
        	if($this->getIsNewRecord($content[3])) {
	        	Yii::$app->db->createCommand("INSERT INTO {{%user_personal_info}} SET
	        		`personal_id` = '" . $content[3] . "',
	        		`first_name` = '" . $content[1] . "',
	        		`last_name` = '" . $content[2] . "',
	        		`birth_date` = '" . $this->birthDate($content[4]) . "',
	        		`mobile` = '" . $this->mobile($content[5]) . "',
	        		`email`	= '" . $content[6] . "',
	        		`father` = '" . $content[22] . "',
	        		`father_mobile` = '" . $this->mobile($content[23]) . "',
	        		`mother` = '" . $content[24] . "',
	        		`mother_mobile` = '" . $this->mobile($content[25]) . "',
	        		`parent` = '" . $content[26] . "',
	        		`parent_mobile` = '" . $this->mobile($content[27]) . "',
	        		`parent_address` = '" . $content[28] . "',
	        		`term` = '" . $this->term . "';")->execute();
	        	
	        	$insert_id = Yii::$app->db->getLastInsertID();
	        	
	        	Yii::$app->db->createCommand("INSERT INTO {{%user_education_info}} SET
	        		`id` = " . (int)$insert_id . ",
	            	`old_school` = '" . $content[7] . "',
	            	`school_provice` = '" . $content[8] . "',
	            	`branch` = '" . $content[9] . "',
	            	`graduate` = '" . $content[10] . "',
	            	`gpa_graduation` = '" . $content[11] . "',
	            	`level` = " . (int)$this->mapLevel($content[12]) . ",
	            	`major` = 'สาขา" . $content[13] . "',
	            	`faculty` = '" . $content[14] ."';")->execute();

	        	$talentSub = empty($content[16]) ? $content[18] : $content[16];
	        	$talentDetail['sub1'] = empty($content[16]) ? $content[19] : '';
	        	$talentDetail['sub2'] = empty($content[16]) ? $content[20] : '';
	        	$talentDetail['honor'] = empty($content[16]) ? $content[21] : $content[17];
	        	
	        	Yii::$app->db->createCommand("INSERT INTO {{%user_talent_info}} SET
	        		`id` = " . (int)$insert_id . ",
	            	`talent_type` = '" . $content[15] . "',
	            	`talent_sub` = '" . $talentSub . "',
	            	`talent_detail` = '" . json_encode($talentDetail, JSON_UNESCAPED_UNICODE) . "';")->execute();
        	} else {
        		$duplicate[$content[3]] = Yii::t('app', 'Error insert data:') . $content[1] .' ' . $content[2] . ' ' . Yii::t('app', 'has duplicated !');
        	}
    	}

    	Yii::$app->session->setFlash('duplicate', $duplicate);
    	unlink(Yii::getAlias('@webroot') . '/uploads/' . $this->fileName);
	}

	protected function birthDate($data)
	{
		list($m, $d, $y) = explode('/', $data);
		$date = date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));

		return $date;
	}

	protected function mobile($data)
	{
		$str = str_replace('-', '', $data);
		if (strlen($str) < 10){
			$str = '0' . $str;
		}

		return $str;
	}

	protected function mapLevel($data)
	{
		$query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_level}} WHERE `name` LIKE '%" . $data . "%';")->queryOne();

		return $query['id'];
	}

	protected function getIsNewRecord($personal_id)
	{
		$result = Yii::$app->db->createCommand("SELECT * FROM {{%user_personal_info}} WHERE `personal_id` = '" . $personal_id . "' AND `term` = '" . $this->term . "';")->execute();
		
		if ($result == 0){
			return true;
		} else {
			return false;
		}

	}
}