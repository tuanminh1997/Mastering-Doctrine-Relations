<?php
/**
 * Created by PhpStorm.
 * User: tuanminh
 * Date: 04/06/2019
 * Time: 17:13
 */
namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper{
    private $cache;

    private $markdown;

    private $mardownLogger;

    private $isDebug;


    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $mardownLogger, bool $isDebug)
    {
        $this->cache=$cache;
        $this->markdown=$markdown;
        $this->logger=$mardownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source):string {

        if(stripos($source,'bacon')!==false){
            $this->logger->info('The are talking about bacon again');
        }

        if($this->isDebug){
            return $this->markdown->transform($source);
        }

        $item= $this->cache->getItem('markdown_'.md5($source));
        if(!$item->isHit()){
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);

        }

        return $item->get();

    }


}