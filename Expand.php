<?php

namespace MediaWiki\Extension\RationalWiki;

$wgExtensionCredits['parserhook'][] = array(
    'name'           => 'Expand',
    'description'    => 'Preprocesses input as template call, to allow recursive substitution with {{subst:expand:Template}}',
);

$wgHooks['ParserFirstCallInit'][] = 'MediaWiki\\Extension\\RationalWiki\\wfExpand';
$wgExtensionMessagesFiles['RationalWikiExpandMagic'] = __DIR__ . '/expand.i18n.magic.php';

/**
 * @param \Parser $parser
 * @return bool
 */
function wfExpand( $parser ) {
	$parser->setFunctionHook(
		'expand',
		'MediaWiki\\Extension\\RationalWiki\\efExpand_Render',
		SFH_NO_HASH);
	return true;
}

/**
 * @param \Parser $parser
 * @return array
 */
function efExpand_Render( &$parser ) {
	$args = func_get_args();
	array_shift($args);
	$text = implode('|',$args);
	$mOt = $parser->mOutputType;
	$parser->setOutputType( OT_PREPROCESS );
	$output = $parser->replaceVariables( "{{{$text}}}" );
	$parser->setOutputType( $mOt );
	return array($output, 'noparse' => true );
}

