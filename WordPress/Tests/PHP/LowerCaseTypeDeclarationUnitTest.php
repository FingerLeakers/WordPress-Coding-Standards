<?php
/**
 * Unit test class for WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPress\Tests\PHP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the LowerCaseTypeDeclaration sniff.
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.14.0
 */
class LowerCaseTypeDeclarationUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			// Parameter type declarations.
			45 => 1,
			// 46 => 1, // Disabled until PHP 5.3/PHPCS 2.x support is dropped.
			47 => 1,
			48 => 1,
			49 => 1,
			50 => 1,
			51 => 1,
			52 => 1,
			55 => 1,

			// Return type declarations.
			84 => 1,
			85 => 1,
			86 => 1,
			87 => 1,
			88 => 1,
			89 => 1,
			90 => 1,
			91 => 1,
			92 => 1,
			94 => 1,
			95 => 1,
		);

	}//end getErrorList()

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();

	}//end getWarningList()

}//end class
