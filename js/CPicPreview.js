//=============================================================================
CPicPreview = {};

//=============================================================================
CPicPreview.Filename = "CPicPreview.php";

//=============================================================================
CPicPreview.GetImageSize = function(Filename, Callback) {
	var ImageObj = new Image();

	ImageObj.src = Filename;

	$(ImageObj).bind("load", function() {
		if(Callback) {
			Callback(ImageObj.width, ImageObj.height);
		}
	});
}

//-----------------------------------------------------------------------------
CPicPreview.GetURL = function(File, Size, Ratio) {
	var URL = CPicPreview.Filename;

	URL += "?CPP_File=" + encodeURIComponent(File) + "&CPP_Size=" + encodeURIComponent(Size) + "&CPP_Ratio=" + encodeURIComponent(Ratio);

	return URL;
}

//=============================================================================
