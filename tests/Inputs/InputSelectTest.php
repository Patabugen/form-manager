<?php
use FormManager\Builder;

class InputSelectTest extends BaseTest
{
    public function testBasic()
    {
        $this->_testElement(Builder::select());
        $this->_testRequired(Builder::select());
    }

    public function testValues()
    {
        $select = Builder::select();

        //Values
        $select->options([
            '' => 'Empty',
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
        ]);

        $select->val('three');
        $this->assertFalse($select->isValid());

        $select->val('One');
        $this->assertFalse($select->isValid());

        $select->val('1');
        $this->assertTrue($select->isValid());
    }

    public function testAllowNewValues()
    {
        $select = Builder::select()->allowNewValues();

        $select->val('new-value');
        $this->assertTrue($select->isValid());
        $this->assertSame($select->val(), 'new-value');
        $this->assertCount(1, $select->options());

        $select->val('');
        $this->assertSame($select->val(), '');
        $this->assertCount(2, $select->options());

        $select->val(0);
        $this->assertSame($select->val(), 0);
        $this->assertCount(3, $select->options());

        $select->val(1);
        $this->assertSame($select->val(), 1);
        $this->assertCount(4, $select->options());

        $select->val('1');
        $this->assertSame($select->val(), 1);
        $this->assertCount(4, $select->options());
    }

    public function testArrayAccess()
    {
        $select = Builder::select();

        $select['new-value'] = 'New Value';
        $option = $select['new-value'];

        $this->assertInstanceOf('FormManager\\Option', $option);
        $this->assertCount(count($select->options()), $select);
        $this->assertSame($select->offsetGet('new-value'), $select['new-value']);

        $this->assertEquals('new-value', $option->attr('value'));
        $this->assertEquals('New Value', $option->html());
    }

    public function testMultiple()
    {
        $select = Builder::select()->multiple()->options([
            '' => 'Empty',
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
        ]);

        $select->val(['', '0']);

        $this->assertSame(['', 0], $select->val());

        $select->val(2);

        $this->assertSame([2], $select->val());
    }
}
