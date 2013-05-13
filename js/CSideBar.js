//=============================================================================
CSideBar = {};

//=============================================================================
CSideBar.Scrolling		= false;

CSideBar.Element		= null;
CSideBar.ElementInner	= null;

CSideBar.ElementScrollUp	= null;
CSideBar.ElementScrollDown	= null;

CSideBar.ElementInnerY	= 0;

//=============================================================================
CSideBar.OnInit = function() {
	CSideBar.Element			= $("#CSideBar").get(0);
	CSideBar.ElementInner		= $("#CSideBar_Inner").get(0);

	CSideBar.ElementScrollUp	= $("#CSideBar_ScrollUp").get(0);
	CSideBar.ElementScrollDown	= $("#CSideBar_ScrollDown").get(0);

	CSideBar.OnLoop();
}

//-----------------------------------------------------------------------------
CSideBar.OnLoop = function() {
	CSideBar.ResetHeight();

	setTimeout(CSideBar.OnLoop, 500);
}

//-----------------------------------------------------------------------------
CSideBar.ResetHeight = function() {
	if(CSideBar.ElementScrollUp == null)	return;
	if(CSideBar.ElementScrollDown == null)	return;

	var Height		= $(CSideBar.Element).height();
	var InnerHeight = $(CSideBar.ElementInner).height();

	var DifHeight	= Height - InnerHeight;

	if(DifHeight >= 0) {
		CSideBar.ElementInnerY = 0;
		CSideBar.ElementInner.style.top = CSideBar.ElementInnerY + "px";
	}

	if(CSideBar.ElementInnerY >= 0) {
		CSideBar.ElementInnerY = 0;

		CSideBar.ElementScrollUp.style.display = "none";
	}else{
		CSideBar.ElementScrollUp.style.display = "block";
	}

	if(CSideBar.ElementInnerY <= DifHeight) {
		CSideBar.ElementInnerY = DifHeight;

		CSideBar.ElementScrollDown.style.display = "none";
	}else{
		CSideBar.ElementScrollDown.style.display = "block";
	}
}

//-----------------------------------------------------------------------------
CSideBar.ScrollUp = function() {
	if(CSideBar.ElementInner == null) return;

	CSideBar.Scrolling = true;

	function OnMove() {
		if(!CSideBar.Scrolling) return;

		CSideBar.ElementInnerY += 5;

		CSideBar.ResetHeight();

		CSideBar.ElementInner.style.top = CSideBar.ElementInnerY + "px";

		setTimeout(OnMove, 10);
	}

	OnMove();
}

//-----------------------------------------------------------------------------
CSideBar.ScrollDown = function() {
	if(CSideBar.ElementInner == null) return;

	CSideBar.Scrolling = true;

	function OnMove() {
		if(!CSideBar.Scrolling) return;

		CSideBar.ElementInnerY -= 5;

		CSideBar.ResetHeight();

		CSideBar.ElementInner.style.top = CSideBar.ElementInnerY + "px";

		setTimeout(OnMove, 10);
	}

	OnMove();
}

//-----------------------------------------------------------------------------
CSideBar.ScrollStop = function() {
	CSideBar.Scrolling = false;
}

//=============================================================================
$(CSideBar.OnInit);

//=============================================================================
