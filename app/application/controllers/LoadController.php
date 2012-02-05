<?php

class LoadController extends Zefir_Controller_Action
{

	public function cssAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$files = $this->_request->getParam('styles', null);
		
		$styles = '';
		foreach(explode(';', $files) as $file)
		{
			$path = APPLICATION_PATH . '/../public' . $file;
			if (file_exists($path) && is_file($path)) 
			{
				$styles .= @file_get_contents($path);
			}
		}
		//$styles = $this->_minify($styles);
		//$styles = $this->_encode($styles);
		
		$expires_offset = 31536000;
		$this->getResponse()
			->setHeader('Content-Type', 'text/css')			
			->setHeader('Cache-Control', 'public, max-age=' . $expires_offset)
			->setHeader('Expires' , gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT')
			->setBody($styles);
	}
	
	public function jsAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$files = $this->_request->getParam('scripts', null);
	
		$scripts = '';
		foreach(explode(';', $files) as $file)
		{
			$path = APPLICATION_PATH . '/../public' . $file;
			if (file_exists($path) && is_file($path))
			{
				$scripts .= @file_get_contents($path);
				$scripts .= "\n\n";
			}
		}
		//$scripts = $this->_minify($scripts);
		//$scripts = $this->_encode($scripts);
	
		$expires_offset = 31536000;
		$this->getResponse()
			->setHeader('Content-Type', 'text/javascript')
			->setHeader('Cache-Control', 'public, max-age=' . $expires_offset)
			->setHeader('Expires' , gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT')
			->setBody($scripts);
	}

	protected function _encode($string)
	{
		if (! ini_get('zlib.output_compression') && 'ob_gzhandler' != ini_get('output_handler') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) {
			if ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') && function_exists('gzdeflate') ) {
				$this->getResponse()->setHeader('Content-Encoding', 'deflate');
				$string = gzdeflate( $string, 3 );
			} elseif ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && function_exists('gzencode') ) {
				$this->getResponse()->setHeader('Content-Encoding', 'gzip');
				$string = gzencode( $string, 3 );
			}
		}
		
		return $string;
	}
	
	protected function _minify($string)
	{
		$string = str_replace("\r\n", "\n", $string);
		$string = str_replace("\n", "", $string);
		$string = preg_replace('/\t/', '', $string);
		
		return $string;
	}
}

