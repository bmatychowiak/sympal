<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginsfSympalMenuItem extends BasesfSympalMenuItem
{
  protected
    $_label = null,
    $_allPermissions;

  public function getAllPermissions()
  {
    if (!$this->_allPermissions)
    {
      $this->_allPermissions = array();
      foreach ($this->Groups as $group)
      {
        foreach ($group->Permissions as $permission)
        {
          $this->_allPermissions[] = $permission->name;
        }
      }
    }
    return $this->_allPermissions;
  }

  public function postSave($event)
  {
    if (sfSympalContext::hasInstance())
    {
      return $this->clearMenuCache();
    }
  }

  public function clearMenuCache()
  {
    if ($menu = $this->getMenu())
    {
      return $menu->clearCache();
    } else {
      return false;
    }
  }

  public function getParentId()
  {
    $node = $this->getNode();

    if (!$node->isValidNode() || $node->isRoot())
    {
      return null;
    }

    $parent = $node->getParent();

    return $parent['id'];
  }
  
  public function getIndentedName()
  {
    return str_repeat('-', $this->getLevel()) . ' ' . $this->getLabel().($this->getLevel() == 0 ? ' ('.$this->getSlug().')':null);
  }

  public function __toString()
  {
    return $this->getIndentedName();
  }

  public function publish()
  {
    $this->date_published = new Doctrine_Expression('NOW()');
    $this->save();
    $this->refresh();
  }

  public function unpublish()
  {
    $this->date_published = null;
    $this->save();
  }

  public function getLabel()
  {
    if (!$this->_label)
    {
      $label = $this->_get('label');
      $this->_label = $label ? $label:$this->name;
    }
    return $this->_label;
  }

  public function getIndented()
  {
    return (string) $this;
  }

  public function getContent()
  {
    return $this->getRelatedContent();
  }

  public function setContent(sfSympalContent $content)
  {
    $this->RelatedContent = $content;
  }

  public function getRoute()
  {
    return $this->getCustomPath();
  }

  public function setRoute($route)
  {
    $this->setCustomPath($route);
  }

  public function getItemRoute()
  {
    if (!$route = $this->getRoute())
    {
      $content = $this->getContent();
      if ($content instanceof sfSympalContent)
      {
        $route = $this->getContent()->getRoute();
      }
    }
    return $route;
  }

  public function getMenu()
  {
    return sfSympalMenuSiteManager::getMenu($this['root_id']);
  }

  public function getBreadcrumbs($subItem = null)
  {
    $breadcrumbs = null;
    // Get the menu this menu item belongs to
    if ($menu = $this->getMenu())
    {
      // Find the node for this menu item
      $node = $menu->findMenuItem($this);

      // Get the breadcrumbs
      if ($node)
      {
        $breadcrumbs = $node->getBreadcrumbs($subItem);
      }
    }
    // If no breadcrumbs generate a blank object
    if (is_null($breadcrumbs))
    {
      $breadcrumbs = sfSympalMenuBreadcrumbs::generate(array());
    }

    return $breadcrumbs;
  }

  public function getName()
  {
    return $this->_get('name');
  }

  public function getIsPrimary()
  {
    return $this->_get('is_primary');
  }

  public function getIsPublished()
  {
    return ($this->getDatePublished() && strtotime($this->getDatePublished()) <= time()) ? true : false;
  }

  public function getIsPublishedInTheFuture()
  {
    return ($this->getDatePublished() && strtotime($this->getDatePublished()) > time()) ? true : false;
  }

  public function getDatePublished()
  {
    return $this->_get('date_published');
  }

  public function getCustomPath()
  {
    return $this->_get('custom_path');
  }

  public function getRequiresAuth()
  {
    return $this->_get('requires_auth');
  }

  public function getRequiresNoAuth()
  {
    return $this->_get('requires_no_auth');
  }

  public function getSiteId()
  {
    return $this->_get('site_id');
  }

  public function getContentTypeId()
  {
    return $this->_get('content_type_id');
  }

  public function getContentId()
  {
    return $this->_get('content_id');
  }

  public function getRelatedContent()
  {
    return $this->_get('RelatedContent');
  }

  public function getSite()
  {
    return $this->_get('Site');
  }

  public function getContentType()
  {
    return $this->_get('ContentType');
  }

  public function getGroups()
  {
    return $this->_get('Groups');
  }

  public function getPermissions()
  {
    return $this->_get('Permissions');
  }

  public function getMenuItemGroups()
  {
    return $this->_get('MenuItemGroups');
  }
}