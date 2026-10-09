<?phprequire_once './models/ServiceModel.php'; 
class PageController {
    private $serviceModel;

    public function __construct($db) {
        $this->serviceModel = new ServiceModel($db);
    }


    public function getHomepageData() {
        return [
            'hero_tagline' => 'Power Your Future with Solar',[cite: 2]
            'services'     => $this->serviceModel->getMainServices(),[cite: 2]
            'benefits'     => ['Cost Savings', 'Sustainability', 'Energy Independence'][cite: 2]
        ];
    }
}
?>