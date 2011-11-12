<?php
/**
 * Configuration for Messages
 */
return array(
	// base name of the element in the session. The name of the instance and the
	// particular group will be added to this.
	'session' => 'messages',
	// Do we show empty groups ?
	'show_empty' => false,
	// Allowed groups
	'groups' => array('notice', 'error', 'success', 'feedback', 'info', 'alert'),
	// Format used for a message
	'message' => '<li>{message}</li>',
	// Format used for a group, messages is the concatenation of all messages
	// see message format above
	'group' => '<ul class="{group}">{messages}</ul>',
	// Format used for all groups, concatanetion of the above format.
	'all_groups' => '{groups}',
);
