<?php
/**
 * Alfred Base Storage Adapter
 *
 * @author David Ferguson (@jdfwarrior)
 * @author Ryan Parman (http://ryanparman.com)
 */

namespace Alfred\Storage;

use Alfred\Exception\JsonException;

abstract class Base
{
	/**
	 * Determines the best location for writing the .plist data.
	 *
	 * @return string The file system path to write the plist data to.
	 */
	public function getStoragePath()
	{
		foreach (array($this->path, $this->data, $this->cache) as $path)
		{
			if (file_exists($path . '/' . $this->plist))
			{
				return $path . '/' . $this->plist;
			}
		}

		return $this->data . '/' . $this->plist;
	}

	/**
	 * Handles throwing JSON-related exceptions, if any.
	 *
	 * @throws JsonException
	 */
	public function handleJsonExceptions()
	{
		switch (json_last_error())
		{
			case JSON_ERROR_NONE:
				break;

			case JSON_ERROR_DEPTH:
				throw new JsonException('Maximum stack depth exceeded.');
				break;

			case JSON_ERROR_STATE_MISMATCH:
				throw new JsonException('Underflow or the modes mismatch.');
				break;

			case JSON_ERROR_CTRL_CHAR:
				throw new JsonException('Unexpected control character found.');
				break;

			case JSON_ERROR_SYNTAX:
				throw new JsonException('Syntax error; Malformed JSON.');
				break;

			case JSON_ERROR_UTF8:
				throw new JsonException('Malformed UTF-8 characters; Possibly incorrectly encoded.');
				break;

			default:
				throw new JsonException('Unknown JSON encoding error.');
				break;
		}
	}
}
