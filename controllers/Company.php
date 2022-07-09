<?php
if(include("../config.php")){
}else{
    include("../../config.php");
}
session_start();

class MyPDO extends PDO{
    const PARAM_host=PARAM_HOST;
    const PARAM_port=PARAM_PORT;
    const PARAM_db_name=PARAM_DB_NAME;
    const PARAM_user=PARAM_DB_USER;
    const PARAM_db_pass=PARAM_DB_PASS;
    const items_per_page=10;
    //create connection with data base in the constractor
    public function __construct($options=null){
        parent::__construct('mysql:host='.MyPDO::PARAM_host,MyPDO::PARAM_user,MyPDO::PARAM_db_pass,$options);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbname=MyPDO::PARAM_db_name;
        parent::query("CREATE DATABASE IF NOT EXISTS $dbname");
        parent::query("use $dbname");
    }
    //company table creation
    public function create_company_tables($name=""){
        if($name===""){
            $this->query("CREATE TABLE IF NOT EXISTS `company`(
               id INT AUTO_INCREMENT PRIMARY KEY,
               name varchar(255) not null,
               category varchar(255) not null,
               phone CHAR (15) NOT NULL,
               city varchar(255),
               area varchar(255),
               images longtext,
               created_at date DEFAULT CURRENT_DATE(),
               updated_at date DEFAULT CURRENT_DATE()
            )"
        );
        }else{

        }

    }
    // add new company
    public function insert_company($params){
        $errors=0;
        $query=$this->prepare("INSERT INTO `company`(`name`, `category`, `phone`, `city`, `area`) VALUES (?,?,?,?,?)");
        $query->execute([$params["name"],$params["categories"],$params["phone"],$params["city"],$params["area"]]);
        $result=$this->query("select max(id) as max from `company`");
        $company_id=$result->fetch()["max"];
        $countfiles = count($_FILES['company_images']['name']);
        $photos='';
        for($i=0;$i<$countfiles;$i++){
            if(!empty($_FILES["company_images"]["name"][$i])){
                $name=time()."_".$company_id."_file_".$i."_".basename($_FILES["company_images"]["name"][$i]);
                $photos.=$name."___";
                $path=$_SERVER["DOCUMENT_ROOT"]."/company/"."public/images/company/".$name;
                if(!move_uploaded_file($_FILES["company_images"]["tmp_name"][$i],$path)){
                    $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in file uploading try again"];
                    $errors=1;
                    header("location:/company/views/");
                }
            }
        }
        if($errors==0){
            $query=$this->query("UPDATE `company` SET `images` = '$photos' WHERE `id`= $company_id");
            $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"company Added successfully"];
            header("location:/company/views/");
        }
    }
    // list all company
    public function get_all_comanies(){
        if(isset($_SESSION["page"])){
            $validnumber=filter_var($_SESSION["page"]["page_number"], FILTER_SANITIZE_NUMBER_INT);
            $current_start=($validnumber-1)*MyPDO::items_per_page;
            $results=$this->query("SELECT * FROM company limit ".$current_start.",".MyPDO::items_per_page);
            return $results->fetchAll();
        }else{
            $results=$this->query("SELECT * FROM company limit ".MyPDO::items_per_page);
            return $results->fetchAll();
        }
    }
    //create city and area tables
    public function adding_cities_areas_tables(){
        $results=$this->query("SHOW TABLES LIKE 'cities'");
        if($results->rowCount() == 0) 
        {
            $this->query("CREATE TABLE IF NOT EXISTS `cities`(
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `governorate_name_ar` varchar(50) NOT NULL,
                `governorate_name_en` varchar(50) NOT NULL)
                ");
            $this->query("CREATE TABLE IF NOT EXISTS `areas`(
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `governorate_id` int(11) NOT NULL,
                `city_name_ar` varchar(200) NOT NULL,
                `city_name_en` varchar(200) NOT NULL
            )");
                $this->adding_cities_areas_entries();
        }
    }
    // add the city and area info
    private function adding_cities_areas_entries(){
        $this->query("INSERT INTO `cities` (`governorate_name_ar`,`governorate_name_en`) VALUES
            ('القاهرة', 'Cairo'),
            ('الجيزة', 'Giza'),
            ('الأسكندرية', 'Alexandria'),
            ('الدقهلية', 'Dakahlia'),
            ('البحر الأحمر', 'Red Sea'),
            ('البحيرة', 'Beheira'),
            ('الفيوم', 'Fayoum'),
            ('الغربية', 'Gharbiya'),
            ('الإسماعلية', 'Ismailia'),
            ('المنوفية', 'Menofia'),
            ('المنيا', 'Minya'),
            ('القليوبية', 'Qaliubiya'),
            ('الوادي الجديد', 'New Valley'),
            ('السويس', 'Suez'),
            ('اسوان', 'Aswan'),
            ('اسيوط', 'Assiut'),
            ('بني سويف', 'Beni Suef'),
            ('بورسعيد', 'Port Said'),
            ('دمياط', 'Damietta'),
            ('الشرقية', 'Sharkia'),
            ('جنوب سيناء', 'South Sinai'),
            ('كفر الشيخ', 'Kafr Al sheikh'),
            ('مطروح', 'Matrouh'),
            ('الأقصر', 'Luxor'),
            ('قنا', 'Qena'),
            ('شمال سيناء', 'North Sinai'),
            ('سوهاج', 'Sohag');");
        $this->query("INSERT INTO `areas` (`governorate_id`,`city_name_ar`,`city_name_en`) VALUES
            /* Start Cairo ID:1 */
            (1, '15 مايو', '15 May'),
            (1, 'الازبكية', 'Al Azbakeyah'),
            (1, 'البساتين', 'Al Basatin'),
            (1, 'التبين', 'Tebin'),
            (1, 'الخليفة', 'El-Khalifa'),
            (1, 'الدراسة', 'El darrasa'),
            (1, 'الدرب الاحمر', 'Aldarb Alahmar'),
            (1, 'الزاوية الحمراء', 'Zawya al-Hamra'),
            (1, 'الزيتون', 'El-Zaytoun'),
            (1, 'الساحل', 'Sahel'),
            (1, 'السلام', 'El Salam'),
            (1, 'السيدة زينب', 'Sayeda Zeinab'),
            (1, 'الشرابية', 'El Sharabeya'),
            (1, 'مدينة الشروق', 'Shorouk'),
            (1, 'الظاهر', 'El Daher'),
            (1, 'العتبة', 'Ataba'),
            (1, 'القاهرة الجديدة', 'New Cairo'),
            (1, 'المرج', 'El Marg'),
            (1, 'عزبة النخل', 'Ezbet el Nakhl'),
            (1, 'المطرية', 'Matareya'),
            (1, 'المعادى', 'Maadi'),
            (1, 'المعصرة', 'Maasara'),
            (1, 'المقطم', 'Mokattam'),
            (1, 'المنيل', 'Manyal'),
            (1, 'الموسكى', 'Mosky'),
            (1, 'النزهة', 'Nozha'),
            (1, 'الوايلى', 'Waily'),
            (1, 'باب الشعرية', 'Bab al-Shereia'),
            (1, 'بولاق', 'Bolaq'),
            (1, 'جاردن سيتى', 'Garden City'),
            (1, 'حدائق القبة', 'Hadayek El-Kobba'),
            (1, 'حلوان', 'Helwan'),
            (1, 'دار السلام', 'Dar Al Salam'),
            (1, 'شبرا', 'Shubra'),
            (1, 'طره', 'Tura'),
            (1, 'عابدين', 'Abdeen'),
            (1, 'عباسية', 'Abaseya'),
            (1, 'عين شمس', 'Ain Shams'),
            (1, 'مدينة نصر', 'Nasr City'),
            (1, 'مصر الجديدة', 'New Heliopolis'),
            (1, 'مصر القديمة', 'Masr Al Qadima'),
            (1, 'منشية ناصر', 'Mansheya Nasir'),
            (1, 'مدينة بدر', 'Badr City'),
            (1, 'مدينة العبور', 'Obour City'),
            (1, 'وسط البلد', 'Cairo Downtown'),
            (1, 'الزمالك', 'Zamalek'),
            (1, 'قصر النيل', 'Kasr El Nile'),
            (1, 'الرحاب', 'Rehab'),
            (1, 'القطامية', 'Katameya'),
            (1, 'مدينتي', 'Madinty'),
            (1, 'روض الفرج', 'Rod Alfarag'),
            (1, 'شيراتون', 'Sheraton'),
            (1, 'الجمالية', 'El-Gamaleya'),
            (1, 'العاشر من رمضان', '10th of Ramadan City'),
            (1, 'الحلمية', 'Helmeyat Alzaytoun'),
            (1, 'النزهة الجديدة', 'New Nozha'),
            (1, 'العاصمة الإدارية', 'Capital New'),
            /* End Cairo ID:1 */

            /* Start Giza ID:2 */
            (2, 'الجيزة', 'Giza'),
            (2, 'السادس من أكتوبر', 'Sixth of October'),
            (2, 'الشيخ زايد', 'Cheikh Zayed'),
            (2, 'الحوامدية', 'Hawamdiyah'),
            (2, 'البدرشين', 'Al Badrasheen'),
            (2, 'الصف', 'Saf'),
            (2, 'أطفيح', 'Atfih'),
            (2, 'العياط', 'Al Ayat'),
            (2, 'الباويطي', 'Al-Bawaiti'),
            (2, 'منشأة القناطر', 'ManshiyetAl Qanater'),
            (2, 'أوسيم', 'Oaseem'),
            (2, 'كرداسة', 'Kerdasa'),
            (2, 'أبو النمرس', 'Abu Nomros'),
            (2, 'كفر غطاطي', 'Kafr Ghati'),
            (2, 'منشأة البكاري', 'Manshiyet Al Bakari'),
            (2, 'الدقى', 'Dokki'),
            (2, 'العجوزة', 'Agouza'),
            (2, 'الهرم', 'Haram'),
            (2, 'الوراق', 'Warraq'),
            (2, 'امبابة', 'Imbaba'),
            (2, 'بولاق الدكرور', 'Boulaq Dakrour'),
            (2, 'الواحات البحرية', 'Al Wahat Al Baharia'),
            (2, 'العمرانية', 'Omraneya'),
            (2, 'المنيب', 'Moneeb'),
            (2, 'بين السرايات', 'Bin Alsarayat'),
            (2, 'الكيت كات', 'Kit Kat'),
            (2, 'المهندسين', 'Mohandessin'),
            (2, 'فيصل', 'Faisal'),
            (2, 'أبو رواش', 'Abu Rawash'),
            (2, 'حدائق الأهرام', 'Hadayek Alahram'),
            (2, 'الحرانية', 'Haraneya'),
            (2, 'حدائق اكتوبر', 'Hadayek October'),
            (2, 'صفط اللبن', 'Saft Allaban'),
            (2, 'القرية الذكية', 'Smart Village'),
            (2, 'ارض اللواء', 'Ard Ellwaa'),
            /* End Giza ID:2 */

            /* Start Alexandria ID:3 */
            (3, 'ابو قير', 'Abu Qir'),
            (3, 'الابراهيمية', 'Al Ibrahimeyah'),
            (3, 'الأزاريطة', 'Azarita'),
            (3, 'الانفوشى', 'Anfoushi'),
            (3, 'الدخيلة', 'Dekheila'),
            (3, 'السيوف', 'El Soyof'),
            (3, 'العامرية', 'Ameria'),
            (3, 'اللبان', 'El Labban'),
            (3, 'المفروزة', 'Al Mafrouza'),
            (3, 'المنتزه', 'El Montaza'),
            (3, 'المنشية', 'Mansheya'),
            (3, 'الناصرية', 'Naseria'),
            (3, 'امبروزو', 'Ambrozo'),
            (3, 'باب شرق', 'Bab Sharq'),
            (3, 'برج العرب', 'Bourj Alarab'),
            (3, 'ستانلى', 'Stanley'),
            (3, 'سموحة', 'Smouha'),
            (3, 'سيدى بشر', 'Sidi Bishr'),
            (3, 'شدس', 'Shads'),
            (3, 'غيط العنب', 'Gheet Alenab'),
            (3, 'فلمينج', 'Fleming'),
            (3, 'فيكتوريا', 'Victoria'),
            (3, 'كامب شيزار', 'Camp Shizar'),
            (3, 'كرموز', 'Karmooz'),
            (3, 'محطة الرمل', 'Mahta Alraml'),
            (3, 'مينا البصل', 'Mina El-Basal'),
            (3, 'العصافرة', 'Asafra'),
            (3, 'العجمي', 'Agamy'),
            (3, 'بكوس', 'Bakos'),
            (3, 'بولكلي', 'Boulkly'),
            (3, 'كليوباترا', 'Cleopatra'),
            (3, 'جليم', 'Glim'),
            (3, 'المعمورة', 'Al Mamurah'),
            (3, 'المندرة', 'Al Mandara'),
            (3, 'محرم بك', 'Moharam Bek'),
            (3, 'الشاطبي', 'Elshatby'),
            (3, 'سيدي جابر', 'Sidi Gaber'),
            (3, 'الساحل الشمالي', 'North Coast/sahel'),
            (3, 'الحضرة', 'Alhadra'),
            (3, 'العطارين', 'Alattarin'),
            (3, 'سيدي كرير', 'Sidi Kerir'),
            (3, 'الجمرك', 'Elgomrok'),
            (3, 'المكس', 'Al Max'),
            (3, 'مارينا', 'Marina'),
            /* End Alexandria ID:3 */

            /* Start Dakahlia ID:4 */
            (4, 'المنصورة', 'Mansoura'),
            (4, 'طلخا', 'Talkha'),
            (4, 'ميت غمر', 'Mitt Ghamr'),
            (4, 'دكرنس', 'Dekernes'),
            (4, 'أجا', 'Aga'),
            (4, 'منية النصر', 'Menia El Nasr'),
            (4, 'السنبلاوين', 'Sinbillawin'),
            (4, 'الكردي', 'El Kurdi'),
            (4, 'بني عبيد', 'Bani Ubaid'),
            (4, 'المنزلة', 'Al Manzala'),
            (4, 'تمي الأمديد', 'tami al\'amdid'),
            (4, 'الجمالية', 'aljamalia'),
            (4, 'شربين', 'Sherbin'),
            (4, 'المطرية', 'Mataria'),
            (4, 'بلقاس', 'Belqas'),
            (4, 'ميت سلسيل', 'Meet Salsil'),
            (4, 'جمصة', 'Gamasa'),
            (4, 'محلة دمنة', 'Mahalat Damana'),
            (4, 'نبروه', 'Nabroh'),
            /* End Dakahlia ID:4 */

            /* Start Red Sea ID:5 */
            (5, 'الغردقة', 'Hurghada'),
            (5, 'رأس غارب', 'Ras Ghareb'),
            (5, 'سفاجا', 'Safaga'),
            (5, 'القصير', 'El Qusiar'),
            (5, 'مرسى علم', 'Marsa Alam'),
            (5, 'الشلاتين', 'Shalatin'),
            (5, 'حلايب', 'Halaib'),
            (5, 'الدهار', 'Aldahar'),
            /* End Red Sea ID:5 */

            /* Start Beheira ID:6 */
            (6, 'دمنهور', 'Damanhour'),
            (6, 'كفر الدوار', 'Kafr El Dawar'),
            (6, 'رشيد', 'Rashid'),
            (6, 'إدكو', 'Edco'),
            (6, 'أبو المطامير', 'Abu al-Matamir'),
            (6, 'أبو حمص', 'Abu Homs'),
            (6, 'الدلنجات', 'Delengat'),
            (6, 'المحمودية', 'Mahmoudiyah'),
            (6, 'الرحمانية', 'Rahmaniyah'),
            (6, 'إيتاي البارود', 'Itai Baroud'),
            (6, 'حوش عيسى', 'Housh Eissa'),
            (6, 'شبراخيت', 'Shubrakhit'),
            (6, 'كوم حمادة', 'Kom Hamada'),
            (6, 'بدر', 'Badr'),
            (6, 'وادي النطرون', 'Wadi Natrun'),
            (6, 'النوبارية الجديدة', 'New Nubaria'),
            (6, 'النوبارية', 'Alnoubareya'),
            /* End Beheira ID:6 */

            /* Start Fayoum ID:7 */
            (7, 'الفيوم', 'Fayoum'),
            (7, 'الفيوم الجديدة', 'Fayoum El Gedida'),
            (7, 'طامية', 'Tamiya'),
            (7, 'سنورس', 'Snores'),
            (7, 'إطسا', 'Etsa'),
            (7, 'إبشواي', 'Epschway'),
            (7, 'يوسف الصديق', 'Yusuf El Sediaq'),
            (7, 'الحادقة', 'Hadqa'),
            (7, 'اطسا', 'Atsa'),
            (7, 'الجامعة', 'Algamaa'),
            (7, 'السيالة', 'Sayala'),
            /* End Fayoum ID:7 */

            /* Start Gharbia ID:8 */
            (8, 'طنطا', 'Tanta'),
            (8, 'المحلة الكبرى', 'Al Mahalla Al Kobra'),
            (8, 'كفر الزيات', 'Kafr El Zayat'),
            (8, 'زفتى', 'Zefta'),
            (8, 'السنطة', 'El Santa'),
            (8, 'قطور', 'Qutour'),
            (8, 'بسيون', 'Basion'),
            (8, 'سمنود', 'Samannoud'),
            /* End Gharbia ID:8 */

            /* Start Ismailia ID:9 */
            (9, 'الإسماعيلية', 'Ismailia'),
            (9, 'فايد', 'Fayed'),
            (9, 'القنطرة شرق', 'Qantara Sharq'),
            (9, 'القنطرة غرب', 'Qantara Gharb'),
            (9, 'التل الكبير', 'El Tal El Kabier'),
            (9, 'أبو صوير', 'Abu Sawir'),
            (9, 'القصاصين الجديدة', 'Kasasien El Gedida'),
            (9, 'نفيشة', 'Nefesha'),
            (9, 'الشيخ زايد', 'Sheikh Zayed'),
            /* End Ismailia ID:9 */

            /* Start Monufya ID:10 */
            (10, 'شبين الكوم', 'Shbeen El Koom'),
            (10, 'مدينة السادات', 'Sadat City'),
            (10, 'منوف', 'Menouf'),
            (10, 'سرس الليان', 'Sars El-Layan'),
            (10, 'أشمون', 'Ashmon'),
            (10, 'الباجور', 'Al Bagor'),
            (10, 'قويسنا', 'Quesna'),
            (10, 'بركة السبع', 'Berkat El Saba'),
            (10, 'تلا', 'Tala'),
            (10, 'الشهداء', 'Al Shohada'),
            /* Start Monufya ID:10 */

            /* Start Minya ID:11 */
            (11, 'المنيا', 'Minya'),
            (11, 'المنيا الجديدة', 'Minya El Gedida'),
            (11, 'العدوة', 'El Adwa'),
            (11, 'مغاغة', 'Magagha'),
            (11, 'بني مزار', 'Bani Mazar'),
            (11, 'مطاي', 'Mattay'),
            (11, 'سمالوط', 'Samalut'),
            (11, 'المدينة الفكرية', 'Madinat El Fekria'),
            (11, 'ملوي', 'Meloy'),
            (11, 'دير مواس', 'Deir Mawas'),
            (11, 'ابو قرقاص', 'Abu Qurqas'),
            (11, 'ارض سلطان', 'Ard Sultan'),
            /* End Minya ID:11 */

            /* Start Qalubia ID:12 */
            (12, 'بنها', 'Banha'),
            (12, 'قليوب', 'Qalyub'),
            (12, 'شبرا الخيمة', 'Shubra Al Khaimah'),
            (12, 'القناطر الخيرية', 'Al Qanater Charity'),
            (12, 'الخانكة', 'Khanka'),
            (12, 'كفر شكر', 'Kafr Shukr'),
            (12, 'طوخ', 'Tukh'),
            (12, 'قها', 'Qaha'),
            (12, 'العبور', 'Obour'),
            (12, 'الخصوص', 'Khosous'),
            (12, 'شبين القناطر', 'Shibin Al Qanater'),
            (12, 'مسطرد', 'Mostorod'),
            /* End Qalubia ID:12 */

            /* Start New Valley ID:13 */
            (13, 'الخارجة', 'El Kharga'),
            (13, 'باريس', 'Paris'),
            (13, 'موط', 'Mout'),
            (13, 'الفرافرة', 'Farafra'),
            (13, 'بلاط', 'Balat'),
            (13, 'الداخلة', 'Dakhla'),
            /* End New Valley ID:13 */

            /* Start South Sinai ID:14 */
            (14, 'السويس', 'Suez'),
            (14, 'الجناين', 'Alganayen'),
            (14, 'عتاقة', 'Ataqah'),
            (14, 'العين السخنة', 'Ain Sokhna'),
            (14, 'فيصل', 'Faysal'),
            /* End South Sinai ID:14 */

            /* Start Aswan ID:15 */
            (15, 'أسوان', 'Aswan'),
            (15, 'أسوان الجديدة', 'Aswan El Gedida'),
            (15, 'دراو', 'Drau'),
            (15, 'كوم أمبو', 'Kom Ombo'),
            (15, 'نصر النوبة', 'Nasr Al Nuba'),
            (15, 'كلابشة', 'Kalabsha'),
            (15, 'إدفو', 'Edfu'),
            (15, 'الرديسية', 'Al-Radisiyah'),
            (15, 'البصيلية', 'Al Basilia'),
            (15, 'السباعية', 'Al Sibaeia'),
            (15, 'ابوسمبل السياحية', 'Abo Simbl Al Siyahia'),
            (15, 'مرسى علم', 'Marsa Alam'),
            /* End Aswan ID:15 */

            /* Start Assiut ID:16 */
            (16, 'أسيوط', 'Assiut'),
            (16, 'أسيوط الجديدة', 'Assiut El Gedida'),
            (16, 'ديروط', 'Dayrout'),
            (16, 'منفلوط', 'Manfalut'),
            (16, 'القوصية', 'Qusiya'),
            (16, 'أبنوب', 'Abnoub'),
            (16, 'أبو تيج', 'Abu Tig'),
            (16, 'الغنايم', 'El Ghanaim'),
            (16, 'ساحل سليم', 'Sahel Selim'),
            (16, 'البداري', 'El Badari'),
            (16, 'صدفا', 'Sidfa'),
            /* End Assiut ID:16 */

            /* Start Bani Sweif ID:17 */
            (17, 'بني سويف', 'Bani Sweif'),
            (17, 'بني سويف الجديدة', 'Beni Suef El Gedida'),
            (17, 'الواسطى', 'Al Wasta'),
            (17, 'ناصر', 'Naser'),
            (17, 'إهناسيا', 'Ehnasia'),
            (17, 'ببا', 'beba'),
            (17, 'الفشن', 'Fashn'),
            (17, 'سمسطا', 'Somasta'),
            (17, 'الاباصيرى', 'Alabbaseri'),
            (17, 'مقبل', 'Mokbel'),
            /* End Bani Sweif ID:17 */

            /* Start PorSaid ID:18 */
            (18, 'بورسعيد', 'PorSaid'),
            (18, 'بورفؤاد', 'Port Fouad'),
            (18, 'العرب', 'Alarab'),
            (18, 'حى الزهور', 'Zohour'),
            (18, 'حى الشرق', 'Alsharq'),
            (18, 'حى الضواحى', 'Aldawahi'),
            (18, 'حى المناخ', 'Almanakh'),
            (18, 'حى مبارك', 'Mubarak'),
            /* End PorSaid ID:18 */

            /* Start Damietta ID:19 */
            (19, 'دمياط', 'Damietta'),
            (19, 'دمياط الجديدة', 'New Damietta'),
            (19, 'رأس البر', 'Ras El Bar'),
            (19, 'فارسكور', 'Faraskour'),
            (19, 'الزرقا', 'Zarqa'),
            (19, 'السرو', 'alsaru'),
            (19, 'الروضة', 'alruwda'),
            (19, 'كفر البطيخ', 'Kafr El-Batikh'),
            (19, 'عزبة البرج', 'Azbet Al Burg'),
            (19, 'ميت أبو غالب', 'Meet Abou Ghalib'),
            (19, 'كفر سعد', 'Kafr Saad'),
            /* End Damietta ID:19 */

            /* Start Sharqia ID:20 */
            (20, 'الزقازيق', 'Zagazig'),
            (20, 'العاشر من رمضان', 'Al Ashr Men Ramadan'),
            (20, 'منيا القمح', 'Minya Al Qamh'),
            (20, 'بلبيس', 'Belbeis'),
            (20, 'مشتول السوق', 'Mashtoul El Souq'),
            (20, 'القنايات', 'Qenaiat'),
            (20, 'أبو حماد', 'Abu Hammad'),
            (20, 'القرين', 'El Qurain'),
            (20, 'ههيا', 'Hehia'),
            (20, 'أبو كبير', 'Abu Kabir'),
            (20, 'فاقوس', 'Faccus'),
            (20, 'الصالحية الجديدة', 'El Salihia El Gedida'),
            (20, 'الإبراهيمية', 'Al Ibrahimiyah'),
            (20, 'ديرب نجم', 'Deirb Negm'),
            (20, 'كفر صقر', 'Kafr Saqr'),
            (20, 'أولاد صقر', 'Awlad Saqr'),
            (20, 'الحسينية', 'Husseiniya'),
            (20, 'صان الحجر القبلية', 'san alhajar alqablia'),
            (20, 'منشأة أبو عمر', 'Manshayat Abu Omar'),
            /* End Sharqia ID:20 */

            /* Start South Sinai ID:21 */
            (21, 'الطور', 'Al Toor'),
            (21, 'شرم الشيخ', 'Sharm El-Shaikh'),
            (21, 'دهب', 'Dahab'),
            (21, 'نويبع', 'Nuweiba'),
            (21, 'طابا', 'Taba'),
            (21, 'سانت كاترين', 'Saint Catherine'),
            (21, 'أبو رديس', 'Abu Redis'),
            (21, 'أبو زنيمة', 'Abu Zenaima'),
            (21, 'رأس سدر', 'Ras Sidr'),
            /* End South Sinai ID:21 */

            /* Start Kafr El Sheikh ID:22 */
            (22, 'كفر الشيخ', 'Kafr El Sheikh'),
            (22, 'وسط البلد كفر الشيخ', 'Kafr El Sheikh Downtown'),
            (22, 'دسوق', 'Desouq'),
            (22, 'فوه', 'Fooh'),
            (22, 'مطوبس', 'Metobas'),
            (22, 'برج البرلس', 'Burg Al Burullus'),
            (22, 'بلطيم', 'Baltim'),
            (22, 'مصيف بلطيم', 'Masief Baltim'),
            (22, 'الحامول', 'Hamol'),
            (22, 'بيلا', 'Bella'),
            (22, 'الرياض', 'Riyadh'),
            (22, 'سيدي سالم', 'Sidi Salm'),
            (22, 'قلين', 'Qellen'),
            (22, 'سيدي غازي', 'Sidi Ghazi'),
            /* End Kafr El Sheikh ID:22 */

            /* Start Matrouh ID:23 */
            (23, 'مرسى مطروح', 'Marsa Matrouh'),
            (23, 'الحمام', 'El Hamam'),
            (23, 'العلمين', 'Alamein'),
            (23, 'الضبعة', 'Dabaa'),
            (23, 'النجيلة', 'Al-Nagila'),
            (23, 'سيدي براني', 'Sidi Brani'),
            (23, 'السلوم', 'Salloum'),
            (23, 'سيوة', 'Siwa'),
            (23, 'مارينا', 'Marina'),
            (23, 'الساحل الشمالى', 'North Coast'),
            /* End Matrouh ID:23 */

            /* Start Luxor ID:24 */
            (24, 'الأقصر', 'Luxor'),
            (24, 'الأقصر الجديدة', 'New Luxor'),
            (24, 'إسنا', 'Esna'),
            (24, 'طيبة الجديدة', 'New Tiba'),
            (24, 'الزينية', 'Al ziynia'),
            (24, 'البياضية', 'Al Bayadieh'),
            (24, 'القرنة', 'Al Qarna'),
            (24, 'أرمنت', 'Armant'),
            (24, 'الطود', 'Al Tud'),
            /* End Luxor ID:24 */

            /* Start Qena ID:25 */
            (25, 'قنا', 'Qena'),
            (25, 'قنا الجديدة', 'New Qena'),
            (25, 'ابو طشت', 'Abu Tesht'),
            (25, 'نجع حمادي', 'Nag Hammadi'),
            (25, 'دشنا', 'Deshna'),
            (25, 'الوقف', 'Alwaqf'),
            (25, 'قفط', 'Qaft'),
            (25, 'نقادة', 'Naqada'),
            (25, 'فرشوط', 'Farshout'),
            (25, 'قوص', 'Quos'),
            /* End Qena ID:25 */

            /* Start North Sinai ID:26 */
            (26, 'العريش', 'Arish'),
            (26, 'الشيخ زويد', 'Sheikh Zowaid'),
            (26, 'نخل', 'Nakhl'),
            (26, 'رفح', 'Rafah'),
            (26, 'بئر العبد', 'Bir al-Abed'),
            (26, 'الحسنة', 'Al Hasana'),
            /* End North Sinai ID:26 */

            /* Start Sohag ID:27 */
            (27, 'سوهاج', 'Sohag'),
            (27, 'سوهاج الجديدة', 'Sohag El Gedida'),
            (27, 'أخميم', 'Akhmeem'),
            (27, 'أخميم الجديدة', 'Akhmim El Gedida'),
            (27, 'البلينا', 'Albalina'),
            (27, 'المراغة', 'El Maragha'),
            (27, 'المنشأة', 'almunsha\'a'),
            (27, 'دار السلام', 'Dar AISalaam'),
            (27, 'جرجا', 'Gerga'),
            (27, 'جهينة الغربية', 'Jahina Al Gharbia'),
            (27, 'ساقلته', 'Saqilatuh'),
            (27, 'طما', 'Tama'),
            (27, 'طهطا', 'Tahta'),
            (27, 'الكوثر', 'Alkawthar');
            /* End Sharqia ID:27 */");
    }
    // list all cities and areas
    public function get_all_cities(){
        $result=$this->query("SELECT * FROM `cities`");
        $areas=$this->query("SELECT * FROM `areas` where governorate_id = 1");
        $data_of_city_arrea=["city"=>$result->fetchAll(),"area"=>$areas->fetchAll()];
        return $data_of_city_arrea;
    }
    // retrun the areas with specific id or city name
    public function getareas($city_id){
        $result=$this->prepare("select * from areas where governorate_id = ?");
        $result->execute([$city_id]);
        return $result->fetchAll();
    }
    //Category table creation 
    public function creat_categories(){
        $this->query("CREATE TABLE IF NOT EXISTS `Categories`(
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` varchar(255) NOT NULL)
            ");
    }
    // add category
    public function add_category($name){
        $result=$this->prepare("INSERT INTO `Categories`(`name`) values (?);");
        $stat=$result->execute([$name]);
        return $stat;
    }
    //return the categories
    public function get_all_categories(){
        $result=$this->query("select * from `Categories`");
        return $result->fetchAll();
    }
    //get city for list table
    public function get_city_by_id($id){
        $result=$this->query("SELECT * FROM `cities` WHERE id=$id");
        return $result->fetch();
    }
    // get area for list table
    public function get_area_by_id($id){
        $result=$this->query("SELECT * FROM  `areas` WHERE id=$id");
        return $result->fetch();
    }
    //numbre of companies for pagination
    public function count_of_companies(){
        $result=$this->query("SELECT count(id) as num_of_companies FROM `company`");
        return $result->fetch();
    }
    //delete company and all files of this company
    public function delete_company($id){
        $result=$this->prepare("SELECT * FROM `company` where id = ?");
        $result->execute([$id]);
        $record=$result->fetch();
        //all images to this company
        $images=explode("___",$record["images"]);
        //iterate over them to delete
        foreach($images as $key=>$value){
            if($value!=""){
                $filename=ROOT_PATH."public/images/company/".$value;
                if (unlink($filename)) {
                    echo "true";
                }else{
                    echo "false";
                }
            }
        }
        $result=$this->prepare("DELETE FROM `company` where id = ?");
        $stat=$result->execute([$id]);
        return $stat;
    }
    //delete images in company
    public function images_delete($id,$name){
        $filename=ROOT_PATH."public/images/company/".$name;
        if (unlink($filename)) {
            $query=$this->prepare("SELECT * FROM `company` where id = ?");
            $query->execute([$id]);
            $record=$query->fetch();
            $new_images=str_replace($name."___","",$record["images"]);
            $query=$this->prepare("UPDATE `company` SET `images` = ? WHERE `id`= ?");
            $stat=$query->execute([$new_images,$id]);
            return $stat;
        }
    }
    //Edit company
    public function Edit_company($credentials){
        $result=$this->prepare("SELECT * FROM `company` where id = ?");
        $result->execute([$credentials["id"]]);
        $record=$result->fetch();
        $old_images=explode("___",$record["images"]);
        //get the last image file in company photos and increment to prevent file overloading  
        $last_image_counter=explode("_",$old_images[count($old_images)-2])[3];
        
        $photos=$record["images"];
        $new_added_images=$credentials["photos"];
        $countfiles = count($new_added_images['name']);
        $errors=0;
        //upload the photos
        for($i=0;$i<$countfiles;$i++){
            if(!empty($new_added_images["name"][$i])){
                $name=time()."_".$credentials["id"]."_file_".($last_image_counter+$i+1)."_".basename($new_added_images["name"][$i]);
                $photos.=$name."___";
                $path=ROOT_PATH."public/images/company/".$name;
                if(!move_uploaded_file($new_added_images["tmp_name"][$i],$path))
                {
                    $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in file uploading try again"];
                    $errors=1;
                    header("location:/company/views/");
                }
            }
        }
        //errors in file uploading and update if no errors
        if($errors==0){
            $category=implode('___',$credentials["categories"]);
            $query=$this->prepare("UPDATE `company` SET `name` = ? , `category` = ? , `phone` = ? , `city` = ? , `area` = ? ,`images` = ? , `updated_at`= CURRENT_DATE()  WHERE `id`=?");
            $stats=$query->execute([$credentials["name"],
                                $category,
                                $credentials["phone"],
                                $credentials["city"],
                                $credentials["area"],
                                $photos,
                                $credentials["id"]
                            ]);
            return $stats;
        }
    }
}
// GET REQUESTS handling
if ($_SERVER['REQUEST_METHOD']=="GET"){
    //the ajax route for the city change
    if(isset($_GET["route"])&&$_GET["route"]=="get_areas"){
        $company=new MyPDO();
        $city_id=htmlspecialchars($_GET["id"]);
        $result=$company->getareas($city_id);
        echo json_encode($result);
    }
    //page change the table containers
    if(isset($_GET["pagination"])){
        $validnumber=filter_var($_GET["pagination"], FILTER_SANITIZE_NUMBER_INT);
        $company=new MyPDO();
        $full_num_comany=$company->count_of_companies()["num_of_companies"];
        $pages_count=ceil($full_num_comany/10);
        if($validnumber>0 && $validnumber<=$pages_count){
            $_SESSION["page"]["page_number"]=$validnumber;
            header("location:/company/views/");
        }else if($validnumber<0){
            $_SESSION["page"]["page_number"]=1;
            header("location:/company/views/");
        }
        else{
            $_SESSION["page"]["page_number"]=$pages_count;
            header("location:/company/views/");
        }
    }
}
// POST REQUESTS handling
else if ($_SERVER['REQUEST_METHOD']=="POST"){
    //clear error session
    if(isset($_SESSION["error"])){
        unset($_SESSION["error"]);
    }
    //clear sussess session
    if(isset($_SESSION["succss"])){
        unset($_SESSION["succss"]);
    }
    //route of add category
    if(isset($_POST["routefor"]) && $_POST["routefor"] === "categories_adding"){
        if($_SESSION["auth"]["role"]=="administrator"){
            $company =new MyPDO();
            if($_POST["name"]==""){
                $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"category name doesn't have value try again"];
                header("location:/company/views/");
            }else{
                $stat=$company->add_category(htmlspecialchars($_POST["name"]));
                if($stat){
                    $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"Added successfully"];
                    header("location:/company/views/");
                    echo 'category add';

                }else{
                    $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"Error in adding the category try again"];
                    header("location:/company/views/");
                }
            }
        }else{
            $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"admin role only"];
            header("location:/company/views/");
        }
    }
    //route for add company
    if(isset($_POST["routefor"]) && $_POST["routefor"] === "company_adding"){
        if($_SESSION["auth"]["role"]=="administrator"){
            $errors=0;
            if($_POST["name"]==""){
                $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"name doesn't support empty name"];
                header("location:/company/views/");
                $errors=1;
            }
            
            $name=htmlspecialchars($_POST["name"]);
            $categories="";
            foreach ($_POST["category"] as $key => $value) {
                $categories.=htmlspecialchars($value).'___';
            }
            $validnumber=filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
            $validnumber == "" ? "error":$validnumber;
            if($validnumber == "error")
            {
                $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"phone number isn't valid"];
                $errors=1;
                header("location:/company/views/");
            }
            $city=htmlspecialchars($_POST["city"]);
            $area=htmlspecialchars($_POST["area"]);
            if($errors==0){
                $company = new MyPDO();
                $company->insert_company(["name"=>$name,"categories"=>$categories,"phone"=>$validnumber,"city"=>$city,"area"=>$area]);
            }
        }
        else{
            $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"admin role only"];
            header("location:/company/views/");
        }
    }

    if(isset($_POST["delete"])){
        if($_SESSION["auth"]["role"]=="administrator"){
            // echo $_POST["delete"];
            $id=filter_var($_POST["delete"], FILTER_SANITIZE_NUMBER_INT);
            $company =new MyPDO();
            $stat=$company->delete_company($id);
            if($stat){
                $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"company Deleted Successfully"];
                header("location:/company/views/");
            }else{
                $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"can't delete company try again"];
                header("location:/company/views/");
            }
        }
        else{
            $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"admin role only"];
            header("location:/company/views/");
        }
    }
    if(isset($_POST["routefor"]) && $_POST["routefor"] === "company_Editing"){
        if($_SESSION["auth"]["role"]=="administrator"){
            $id= htmlspecialchars($_POST["id"]);
            $name=htmlspecialchars($_POST["name"]);
            $city=htmlspecialchars($_POST["city"]);
            $area=htmlspecialchars($_POST["area"]);
            $phone=htmlspecialchars($_POST["phone"]);
            $categories=$_POST["category"];
            $photos=$_FILES['company_images'];
            $credentials=[
                "id"=>$id,
                "name"=>$name,
                "city"=>$city,
                "area"=>$area,
                "phone"=>$phone,
                "categories"=>$categories,
                "photos"=>$photos
            ];
            $company =new MyPDO();
            $stats=$company->Edit_company($credentials);
            if($stats){
                $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"company edited successfully"];
                header("location:/company/views/");
            }else{
                $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"company doesn't edited successfully try again"];
                header("location:/company/views/");
            }
        }
        else{
            $_SESSION["error"]=["page"=>"company","target"=>"category","message"=>"admin role only"];
            header("location:/company/views/");
        }
    }
    //route for delete image from company images
    if(isset($_POST["route"])&&$_POST["route"] === "delete_img"){
        $name=htmlspecialchars($_POST["name"]);
        $id=explode("_",$name)[1];
        $company =new MyPDO();
       $stat=$company->images_delete($id,$name);
       echo $stat;
    }
}
?>