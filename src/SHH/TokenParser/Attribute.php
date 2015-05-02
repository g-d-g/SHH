<?php
namespace SHH\TokenParser;
use SHH\TokenParser;
use SHH\Node;

/*
 * This file is part of SHH.
 * (c) 2015 Dominique Schmitz <info@domizai.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Attribute TokenParser
 */
class Attribute extends TokenParser
{
	const TYPE = ATTRIBUTE_TYPE;
	public $respectIndent = true;

	/**
	 * Parse token.
	 *
	 * @param 	Parser 	$parser 	a Parser instance
	 *
	 * @return 	Node  	an Element or an Attribute Node
	 */
	public function parse(\SHH\Parser &$parser)
	{
		if( $parser->prevIs(array(new TokenParser\EOL, new TokenParser\GroupOpen, new TokenParser\GroupClose, new TokenParser\Tail)) ){
			$parser->injectToken( new TokenParser\Identifier($parser->defaultElement, $this->line, $this->indent) );
			return $parser->parseCurrent();
		}

		if( $token = $parser->expect( array(new TokenParser\PhpShorthand, new TokenParser\Identifier, new TokenParser\SingleQuote, new TokenParser\DoubleQuote) ) ){			
			if( $parser->is( new TokenParser\Identifier ) ){
				$name = $token->tok;
			} else {
				$n = $parser->parseCurrent();
				$name = $n->value;
			}

			if( $parser->nextIs(new TokenParser\Assign, null, true) ){
				$v = $parser->parseCurrent();
				$value = $v->value;
			}

			return new \SHH\Node\Attribute( $name, $value );
		}
	}
}