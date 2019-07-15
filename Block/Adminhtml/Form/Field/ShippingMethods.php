<?php
namespace Beecom\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Cocur\Slugify\Slugify;


class Data extends AbstractHelper
{
    protected $logger;

    protected $scopeConfig;

    protected $serializer;

    protected $slugify;

    protected $arrayMap = null;

    protected $arrayMapCache = null;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context,
        Json $serializer,
        Slugify $slugify,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->slugify = $slugify;
    }

    public function getConfigMap($path, $storeId = null){
        if(!isset($this->arrayMapCache[$path])){
            $this->arrayMapCache[$path] = [];
            $configValue = $this->getMap($path, $storeId);
            foreach ($configValue as $map){
                $this->arrayMapCache[$path][(string) $map['method']] = $map['code'];
            }
        }
        return $this->arrayMapCache[$path];
    }

    public function getConfigMapArray($path, $storeId = null){
        if(!isset($this->arrayMapCache[$path])){
            $this->arrayMapCache[$path] = [];
            $configValue = $this->getMap($path, $storeId);
            foreach ($configValue as $map){
                $this->arrayMapCache[$path][][(string) $map['method']] = $map['code'];
            }
        }
        return $this->arrayMapCache[$path];
    }

    protected function getMap($path, $store = null){
        try{
            $configValue = $this->serializer->unserialize(
                $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $store)
            );
            return $configValue;
        }catch (InvalidArgumentException $invalidArgumentException){
            $this->logger->error($invalidArgumentException->getMessage());
        }
    }

    public function getMapValueByCode($path, $needle, $storeId = null){
        $map = $this->getConfigMap($path, $storeId);
        if(isset($map[$needle])){
            return $map[$needle];
        }
        return null;
    }

    public function getMapValueByValue($path, $needle, $storeId = null){
        $map = $this->getConfigMapArray($path, $storeId);
        $hits = [];
        foreach ($map as $mapValue){
            var_dump($mapValue);
            $hits[] = $mapValue;
        }
        if(count($hits) > 0){
            return $hits;
        }else{
            return null;
        }
    }

    public function slugify($string, $options = null){
        return $this->slugify->slugify($string, $options);
    }

    public function getSlugify(){
        return $this->slugify;
    }

}
