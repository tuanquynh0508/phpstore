<?php

function renderActive($item, $urlBase)
{
	$html = '<a href="'.$urlBase.'?action=active&id='.$item->id.'">';
	$html .= '<img src="img/admin/'.(($item->is_active === 1)?'unlock':'lock').'.png">';
	$html .= '</a>';

	return $html;
}

