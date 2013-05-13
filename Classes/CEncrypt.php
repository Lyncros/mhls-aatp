<?
	//==========================================================================
	/*
		A basic class for encrypting Data

		DO NOT change the key below

		4/10/2009
	*/
	//==========================================================================
	class CEncrypt {
		private static $Key = "128edh19d8hrf3198fh32hgf9823gf090hdF:0?BpkT1,8y";

		/**
		 *  Encryption
		 *
		 *  @static
		 *	@param string|int $input
		 *	@return string
		 */
		public static function Encrypt($input)
		{
			if (!$input)
			{
				return $input;
			}
			
			$td = mcrypt_module_open('rijndael-256', '', 'ofb', '');  
			
			$iv = substr(self::$Key, 0, mcrypt_enc_get_iv_size($td));
			$key = substr(md5(self::$Key), 0, mcrypt_enc_get_key_size($td));
			
			mcrypt_generic_init($td, $key, $iv);
			
			$encrypted_data = mcrypt_generic($td, $input);
			
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			
			return $encrypted_data;
		}
		
		/**
		 *  Decryption
		 *
		 *  @static
		 *	@param string|int $input
		 *	@return string
		 */
		public static function Decrypt($input)
		{
			if (!$input)
			{
				return $input;
			}
			
			$td = mcrypt_module_open('rijndael-256', '', 'ofb', '');
			
			$iv = substr(self::$Key, 0, mcrypt_enc_get_iv_size($td));
			$key = substr(md5(self::$Key), 0, mcrypt_enc_get_key_size($td));
			
			mcrypt_generic_init($td, $key, $iv);
			
			$decrypted_data = mdecrypt_generic($td, $input);
			
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			
			return trim( $decrypted_data );
		}
	};

	//==========================================================================
?>
