<?
	if(CAttention::HasNotices(CSecurity::GetBusinessesID())) {
		CBox::Alert("You have one or more items that require your Attention", "View", "CModule.Load('Attention');");
	}
?>
