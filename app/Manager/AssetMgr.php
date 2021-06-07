<?php

namespace App\Manager;

class AssetMgr
{

    protected $commonMgr;

    public function __construct(CommonMgr $commonMgr)
    {
        $this->commonMgr = $commonMgr;
    }


    /**
     * @desc this function return proper name after removing extra symbols form file name
     * @param $file
     * @return string
     */
    public function getProperName($file)
    {
        $name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        //remove extension
        $getOnlyFileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
        //remove extra symbols
        $repair=array(".",","," ",";","'","\\","\"","/","(",")","?");
        $name=str_replace($repair,"",$getOnlyFileName);
        return time().'_'.$name.'.'.$file_ext;
    }

    public function getFileInfo($file)
    {
        $result['file_name'] = $this->getProperName($file);
        $result['file_extension'] = $file->getClientOriginalExtension();
        $result['file_type'] = $this->commonMgr->getFileType($result['file_extension']);
        return $result;
    }

}
