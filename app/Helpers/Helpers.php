<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Admin;
use App\Models\GmsOffice;
use Illuminate\Support\Facades\DB;

public function userId() { 
	 
	$sessionObject = session()->get('session_token');
	return $sessionObject ;
  
   }

?>