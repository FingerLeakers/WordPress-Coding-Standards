<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPress\Sniffs\PHP;

use WordPress\Sniff;

/**
 * Verify that type declarations are lowercase.
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.14.0
 *
 * {@internal This sniff is a duplicate of the same sniff as pulled upstream.
 * Once the upstream sniff has been merged and the minimum WPCS PHPCS requirement has gone up to
 * the version in which the sniff was merged, this version can be safely removed.
 * {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1685} }}
 */
class LowerCaseTypeDeclarationSniff extends Sniff {

	/**
	 * A list of parameter type declarations to examine.
	 *
	 * Used to be able to distinguish the target type
	 * declarations from class name type declarations.
	 *
	 * Class based parameter type declarations were introduced in PHP 5.0.
	 * All other types have become available after.
	 *
	 * The key is a lowercase type hint, the value is the token code
	 * this type declaration will have in the token stack.
	 *
	 * @since 0.14.0
	 *
	 * @var array
	 */
	protected $parameterTypes = array(
		'self'     => T_SELF,

		// PHP 5.1.
		'array'    => T_ARRAY_HINT,

		// PHP 5.4.
		'callable' => T_CALLABLE,

		// PHP 7.0.
		'bool'     => T_STRING,
		'float'    => T_STRING,
		'int'      => T_STRING,
		'string'   => T_STRING,

		// PHP 7.1.
		'iterable' => T_STRING,

		// PHP 7.2.
		'object'   => T_STRING,
	);

	/**
	 * A list of return type declarations to examine.
	 *
	 * Used to be able to distinguish the target type
	 * declarations from class name type declarations.
	 *
	 * Return declarations were introduced in PHP 7.0.
	 * Some additional types have become available after.
	 *
	 * @since 0.14.0
	 *
	 * @var array
	 */
	protected $returnTypes = array(
		'array'    => true,
		'bool'     => true,
		'callable' => true,
		'float'    => true,
		'int'      => true,
		'parent'   => true,
		'self'     => true,
		'string'   => true,

		// PHP 7.1.
		'iterable' => true,
		'void'     => true,

		// PHP 7.2.
		'object'   => true,
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 0.14.0
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_FUNCTION,
			T_CLOSURE,
			T_RETURN_TYPE,
		);

	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @since 0.14.0
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		if ( T_FUNCTION === $this->tokens[ $stackPtr ]['code']
			|| T_CLOSURE === $this->tokens[ $stackPtr ]['code']
		) {
			// Get all parameters from method signature.
			$paramNames = $this->phpcsFile->getMethodParameters( $stackPtr );
			if ( empty( $paramNames ) ) {
				return;
			}

			foreach ( $paramNames as $param ) {
				if ( '' === $param['type_hint'] ) {
					continue;
				}

				// Strip off potential nullable indication.
				$typeHint   = ltrim( $param['type_hint'], '?' );
				$typeHintLC = strtolower( $typeHint );

				if ( isset( $this->parameterTypes[ $typeHintLC ] ) && $typeHintLC !== $typeHint ) {
					$typePtr = $this->phpcsFile->findPrevious(
						$this->parameterTypes[ $typeHintLC ],
						( $param['token'] - 1 ),
						$stackPtr,
						false,
						$typeHint,
						true
					);
					if ( false === $typePtr ) {
						continue;
					}

					$error = 'Parameter type declarations must be lowercase; expected "%s" but found "%s"';
					$data  = array(
						strtolower( $param['type_hint'] ),
						$param['type_hint'],
					);

					$fix = $this->phpcsFile->addFixableError( $error, $typePtr, 'ParameterTypeFound', $data );
					if ( true === $fix ) {
						$this->phpcsFile->fixer->replaceToken( $typePtr, $typeHintLC );
					}
				}
			}
		} else {
			// Return type.
			$content   = $this->tokens[ $stackPtr ]['content'];
			$contentLC = strtolower( $content );

			if ( isset( $this->returnTypes[ $contentLC ] ) && $contentLC !== $content ) {
				$error = 'Return type declarations must be lowercase; expected "%s" but found "%s"';
				$data  = array(
					$contentLC,
					$content,
				);

				$fix = $this->phpcsFile->addFixableError( $error, $stackPtr, 'ReturnTypeFound', $data );
				if ( true === $fix ) {
					$this->phpcsFile->fixer->replaceToken( $stackPtr, $contentLC );
				}
			}
		}
	}

}//end class
