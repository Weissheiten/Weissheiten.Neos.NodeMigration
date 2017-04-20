<?php

namespace Weissheiten\Neos\NodeMigration\Tests\Unit\Migration\Filter;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeData;
use Weissheiten\Neos\NodeMigration\Migration\Filters;

class HasParentOfNodeTypeTests extends \Neos\Flow\Tests\UnitTestCase
{

    /**
     * @test
     */
    public function hasParentOfGivenNodeTypeOnFirstLevel()
    {
        $mockTree = $this->getMockTree();

        $hasParentOfNodeTypeFilter = new \Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType();
        $hasParentOfNodeTypeFilter->setNodeType('Neos.NodeTypes:Page');
        $hasParentOfNodeTypeFilter->setWithSubTypes(false);
        $hasParentOfNodeTypeFilter->setSearchDepth(1);

        $this->assertTrue($hasParentOfNodeTypeFilter->matches($mockTree['firstLevelNode']));
    }

    /**
     * @test
     */
    public function hasParentOfGivenNodeTypeOnSecondLevel()
    {
        $mockTree = $this->getMockTree();

        $hasParentOfNodeTypeFilter = new \Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType();
        $hasParentOfNodeTypeFilter->setNodeType('Neos.NodeTypes:Page');
        $hasParentOfNodeTypeFilter->setWithSubTypes(false);
        $hasParentOfNodeTypeFilter->setSearchDepth(99);

        $this->assertTrue($hasParentOfNodeTypeFilter->matches($mockTree['secondLevelNode']));
    }

    /**
     * @test
     */
    public function hasParentOfGivenNodeTypeOnSecondLevelButSearchDepthTooLow()
    {
        $mockTree = $this->getMockTree();

        $hasParentOfNodeTypeFilter = new \Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType();
        $hasParentOfNodeTypeFilter->setNodeType('Neos.NodeTypes:Page');
        $hasParentOfNodeTypeFilter->setWithSubTypes(false);
        $hasParentOfNodeTypeFilter->setSearchDepth(1);

        $this->assertFalse($hasParentOfNodeTypeFilter->matches($mockTree['secondLevelNode']));
    }

    /**
     * @test
     */
    public function hasNotParentOfGivenNodeType(){
        $mockTree = $this->getMockTree();

        $hasParentOfNodeTypeFilter = new \Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType();
        $hasParentOfNodeTypeFilter->setNodeType('Neos.NodeTypes:Image');
        $hasParentOfNodeTypeFilter->setWithSubTypes(false);
        $hasParentOfNodeTypeFilter->setSearchDepth(99);

        $this->assertFalse($hasParentOfNodeTypeFilter->matches($mockTree['thirdLevelNode']));
    }


    /**
     * @test
     */
    public function hasParentofSubType(){
        $mockTree = $this->getMockTree();
        $hasParentOfNodeTypeFilter = new \Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType();
        $hasParentOfNodeTypeFilter->setNodeType('Neos.NodeTypes:Page');
        $hasParentOfNodeTypeFilter->setWithSubTypes(true);
        $hasParentOfNodeTypeFilter->setSearchDepth(2);

        $this->assertTrue($hasParentOfNodeTypeFilter->matches($mockTree['thirdLevelNode']));
    }

    /**
     * Gets a mock tree used for all unit tests
     *
     * @return array
     */
    private function getMockTree(){
        // @var PHPUnit_Framework_MockObject_MockObject|NodeData $mockNodeData
        $nodes['siteNode'] = $this->getMockBuilder(NodeData::class)->disableOriginalConstructor()->getMock();
        $nodes['siteNode']->expects($this->any())->method('getNodeType')->will($this->returnValue(new \Neos\ContentRepository\Domain\Model\NodeType('Neos.NodeTypes:Page',[],[])));

        // @var PHPUnit_Framework_MockObject_MockObject|NodeData $mockNodeData
        $nodes['firstLevelNode'] = $this->getMockBuilder(NodeData::class)->disableOriginalConstructor()->getMock();
        $nodes['firstLevelNode']->expects($this->any())->method('getNodeType')->will($this->returnValue(new \Neos\ContentRepository\Domain\Model\NodeType('Custom.Package:SpecialPage',array(0 => new \Neos\ContentRepository\Domain\Model\NodeType('Neos.NodeTypes:Page',[],[])),[])));
        $nodes['firstLevelNode']->expects($this->any())->method('getParent')->will($this->returnValue($nodes['siteNode']));

        // @var PHPUnit_Framework_MockObject_MockObject|NodeData $mockNodeData
        $nodes['secondLevelNode'] = $this->getMockBuilder(NodeData::class)->disableOriginalConstructor()->getMock();
        $nodes['secondLevelNode']->expects($this->any())->method('getNodeType')->will($this->returnValue(new \Neos\ContentRepository\Domain\Model\NodeType('Neos.Neos:ContentCollection',[],[])));
        $nodes['secondLevelNode']->expects($this->any())->method('getParent')->will($this->returnValue($nodes['firstLevelNode']));

        // @var PHPUnit_Framework_MockObject_MockObject|NodeData $mockNodeData
        $nodes['thirdLevelNode'] = $this->getMockBuilder(NodeData::class)->disableOriginalConstructor()->getMock();
        $nodes['thirdLevelNode']->expects($this->any())->method('getNodeType')->will($this->returnValue(new \Neos\ContentRepository\Domain\Model\NodeType('Neos.NeosNodeTypes:Text',[],[])));
        $nodes['thirdLevelNode']->expects($this->any())->method('getParent')->will($this->returnValue($nodes['secondLevelNode']));

        return $nodes;
    }
}
?>