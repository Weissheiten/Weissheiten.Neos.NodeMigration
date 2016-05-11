<?php
namespace Weissheiten\Neos\NodeMigration\Migration\Filters;

/*
 * This file is part of the Weissheiten.Neos.NodeMigration package.
 *
 * (c) Florian Weiss | Weissheiten
 *
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Migration\Filters\FilterInterface;

/**
 * Filter nodes with a parent of a specific NodeType nodes
 */
class HasParentOfNodeType implements FilterInterface
{
    /**
     * The parent node type to match on.
     *
     * @var string
     */
    protected $nodeTypeName;


    /**
     * search depth
     *
     * @var int
     */
    protected $searchDepth;

    /**
     * Sets the node type name to match on.
     *
     * @param string $nodeTypeName
     * @return void
     */
    public function setNodeType($nodeTypeName)
    {
        $this->nodeTypeName = $nodeTypeName;
    }

    /**
     * Whether the filter should match also on all subtypes of the configured
     * node type.
     *
     * Note: This can only be used with node types still available in the
     * system!
     *
     * @param boolean $withSubTypes
     * @return void
     */
    public function setWithSubTypes($withSubTypes)
    {
        $this->withSubTypes = $withSubTypes;
    }

    /**
     * If set to true also all subtypes of the given nodeType will match.
     *
     * @var boolean
     */
    protected $withSubTypes = false;

    /**
     * @param int $searchDepth
     * @return void
     */
    public function setSearchDepth($searchDepth){
        $this->searchDepth = $searchDepth;
    }


    /**
     * Returns TRUE if the given node has a parent of a specific nodetype
     *
     * @param \TYPO3\TYPO3CR\Domain\Model\NodeData $node
     * @return boolean
     */
    public function matches(\TYPO3\TYPO3CR\Domain\Model\NodeData $node)
    {
        $nodeIsMatchingNodeType = false;

        for($i=0; $i<$this->searchDepth;$i++){
            $parentNode = $node->getParent();

            if($parentNode!==null){
                // This will break atm for NodeTypes that no longer exist - see /Packages/Application/TYPO3.TYPO3CR/Classes/TYPO3/TYPO3CR/Migration/Filters/NodeType.php
                // version stated there is a "hack" though and does not allow for standard UnitTesting
                $nodeType = $parentNode->getNodeType();

                if ($this->withSubTypes === true) {
                    $nodeIsMatchingNodeType = $nodeType->isOfType($this->nodeTypeName);
                } else {
                    if ($nodeType->getName() === $this->nodeTypeName) {
                        $nodeIsMatchingNodeType = true;
                        break;
                    }
                }
                $node = $parentNode;
            }
            else{
                break;
            }
        }

        return $nodeIsMatchingNodeType;
    }
}
