<?php

/**
 * BasesfSympalPage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * 
 * @method string       getTitle() Returns the current record's "title" value
 * @method sfSympalPage setTitle() Sets the current record's "title" value
 * 
 * @package    sympal
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7200 2010-02-21 09:37:37Z beberlei $
 */
abstract class BasesfSympalPage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sf_sympal_page');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $sfsympalcontenttypetemplate0 = new sfSympalContentTypeTemplate();
        $this->actAs($sfsympalcontenttypetemplate0);
    }
}