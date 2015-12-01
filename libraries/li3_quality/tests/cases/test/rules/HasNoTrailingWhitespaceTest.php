<?php

namespace li3_quality\tests\cases\test\rules;

class HasNoTrailingWhitespaceTest extends \li3_quality\test\Unit {

	public $rule = 'li3_quality\test\rules\HasNoTrailingWhitespace';

	public function testWithoutWhiteSpace() {
		$code = 'class foobar {}';
		$this->assertRulePass($code, $this->rule);
	}

	public function testWithWhiteSpace() {
		$code = 'class foobar {}   ';
		$this->assertRuleFail($code, $this->rule);
	}

	public function testNewLineAfterCloseTag() {
		$code = "<?php\necho false;\n?>\n";
		$this->assertRuleFail(array(
			'source' => $code,
			'wrap' => false,
		), $this->rule);
	}

	public function testSpacingAfterCloseTag() {
		$code = "<?php\necho false;\n?> ";
		$this->assertRuleFail(array(
			'source' => $code,
			'wrap' => false,
		), $this->rule);
	}

	public function testTabAfterCloseTag() {
		$code = "<?php\necho false;\n?>\t";
		$this->assertRuleFail(array(
			'source' => $code,
			'wrap' => false,
		), $this->rule);
	}

}

?>