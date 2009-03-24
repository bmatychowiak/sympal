<?php

function sympal_content_slot($content, $name, $type = 'Text', $defaultValue = '[Double click to edit slot content]')
{
  $user = sfContext::getInstance()->getUser();

  $slotsCollection = $content->getSlots();
  $slots = array();
  foreach ($slotsCollection as $slot)
  {
    $slots[$slot['name']] = $slot;
  }

  if (!isset($slots[$name]))
  {
    $slot = new ContentSlot();
    $slot->content_id = $content->id;

    if (!$slot->exists())
    {
      $type = Doctrine::getTable('ContentSlotType')->findOneByName($type);
      if (!$type)
      {
        $type = new ContentSlotType();
        $type->setName($type);
      }
      $slot->setType($type);
      $slot->setName($name);
    }

    $slot->save();
  } else {
    $slot = $slots[$name];
  }

  if (sfSympalTools::isEditMode() && $content->userHasLock(sfContext::getInstance()->getUser()->getGuardUser()))
  {
    if ($slot->getValue())
    {
      $contentSlot = sympal_render_content_slot($slot);
    } else {
      $contentSlot = $defaultValue;
    }

    $html  = '<div class="sympal_editable_content_slot" onMouseOver="javascript: highlight_sympal_content_slot(\''.$slot['id'].'\');" onMouseOut="javascript: unhighlight_sympal_content_slot(\''.$slot['id'].'\');" title="Double click to edit this slot named `'.$name.'`" id="edit_content_slot_button_'.$slot['id'].'" style="cursor: pointer;" onClick="javascript: edit_sympal_content_slot(\''.$slot['id'].'\');">';
    $html .= $contentSlot;
    $html .= '</div>';

    $editor  = '<div class="sympal_edit_slot_box yui-skin-sam">';
    $editor .= '<div id="edit_content_slot_'.$slot['id'].'">';
    $editor .= '<div class="hd">Edit Slot: '.$slot['name'].'</div>';
    $editor .= '<div class="bd" id="edit_content_slot_content_'.$slot['id'].'"></div>';
    $editor .= '</div>';
    $editor .= '</div>';

    $editor .= sprintf(<<<EOF
<script type="text/javascript">
myPanel = new YAHOO.widget.Panel('edit_content_slot_%s', {
	underlay:"shadow",
	close:true,
	visible:true,
	context:['edit_content_slot_button_%s', 'tl', 'tl'],
  autofillheight: "body",
  constraintoviewport: true,
	draggable:true} );

myPanel.cfg.setProperty("underlay", "matte");
myPanel.render();
myPanel.hide();

YAHOO.util.Event.addListener("edit_content_slot_button_%s", "dblclick", myPanel.show, myPanel, true);
YAHOO.util.Event.addListener("edit_content_slot_editor_panel_button_%s", "click", myPanel.show, myPanel, true);
</script>
EOF
    ,
      $slot['id'],
      $slot['id'],
      $slot['id'],
      $slot['id'],
      $slot['id'],
      $slot['id']
    );

    slot('sympal_editors', get_slot('sympal_editors').$editor);

    return $html;
  } else {
    return sympal_render_content_slot($slot);
  }
}

function sympal_render_content_slot($contentSlot)
{
  return $contentSlot->render();
}