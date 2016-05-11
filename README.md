Weissheiten.Neos.NodeMigration
===============================

Custom NodeMigration operations for Neos extending the options in the core

DISCLAIMER:
-----------

This package extends the possibilities from the Neos core but is NOT reviewed by core team members.
Included new options are UnitTested and tested in personal projects - use at your own risk however and ALWAYS make a backup before running an operation.


How-To:
-------

* Install the package to ``Packages/Plugin/Weissheiten.Neos.NodeMigration`` (e.g. via ``composer require weissheiten/neos-nodemigration:~1.0``)
* Write your migrations as stated on: http://neos.readthedocs.io/en/stable/References/NodeMigrations.html and using the options listed below
* check the available migrations on the console via ``./flow node:migrationstatus``
* apply your migration via ``./flow node:migrate [yourmigrationid]``


New filter options
------------------

Has Parent of NodeType
-----------------------
Use this to select Nodes that have a parent of a specific NodeType (e.g.: You want to convert all TextNodes which are children on one of your custom Page NodeTypes)

``type: '\Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType'``

settings:

nodeType: The NodeType of the parent node
searchDepth: The depth for which to search upwards in the tree. (eg: Page => ContentCollection => Text --> searchDepth 1 will not return true, searchDepth 2 will if you search for "Page")
withSubTypes: also triggers if the Node is a Subtype of the given nodeType (e.g.: Your "SpecialPage" inheriting from "Page")

Example of usage that converts all "Text" Nodes that have a parent type of "AbstractNews" into "NewsText" Nodes

```yaml
up:
  comments: 'Migrate Textnodes inside news to new NewsText content type.'
  migration:
    -
      filters:
        -
          type: 'NodeType'
          settings:
            nodeType: 'TYPO3.Neos.NodeTypes:Text'
        -
          type: '\Weissheiten\Neos\NodeMigration\Migration\Filters\HasParentOfNodeType'
          settings:
            nodeType: 'Weissheiten.Neos.News:AbstractNews'
            searchDepth: 2
            withSubTypes: true
      transformations:
        -
          type: 'ChangeNodeType'
          settings:
            newType: 'Weissheiten.Neos.News:NewsText'
```