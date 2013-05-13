<?
	$App = $this->Parent->Parent;

	$FileControlTheme	= $this;
	$ThemePath			= $FileControlTheme->Path;

	$Session = new CSession("CSystem");
?>
			<div style="clear: both"></div>
		</div>

		<div style="clear: both"></div>
	</div>
</div>
<div id='Footer'>
	The Almighty App for All Things Project 2012
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href='/Login?Action=Logout'>Log Out</a>
</div>
<br/><br/><br/><br/>

<input type="hidden" id="CSystem_KeepSessionAlive" value="<?=$Session->KeepSessionAlive;?>"/>

<div id="CWindow_Area"></div>
<div id="Blackout"></div>

</body>
</html>
