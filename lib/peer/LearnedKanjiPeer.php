<?php
/**
 * LearnedKanji Peer.
 * 
 * 
 * @author  Fabrice Denis
 */

class LearnedKanjiPeer extends coreDatabaseTable
{
  protected
    $tableName = 'learnedkanji',
    $columns   = array();  // timestamp columns must be declared for insert/update/replace

  /**
   * This function must be copied in each peer class.
   */
  public static function getInstance()
  {
    return coreDatabaseTable::_getInstance(__CLASS__);
  }

  /**
   * Returns count of relearned kanji for given user.
   * 
   * @return mixed  Count, or FALSE on failure
   */
  public static function getCount($userId)
  {
    return (int) self::getInstance()->count('userid = ?', $userId);
  }

  /**
   * 
   * @return boolean
   */
  public static function addKanji($userId, $ucsId)
  {
    assert('(int)$ucsId > 0x3000');

    return self::$db->query('REPLACE INTO '.self::getInstance()->getName().' (userid, ucs_id) VALUES (?, ?)',
      array($userId, $ucsId));
  }

  /**
   * 
   * @return
   */
  public static function hasKanji($userId, $ucsId)
  {
    assert('(int)$ucsId > 0x3000');

    return self::getInstance()->count('userid = ? AND ucs_id = ?', array($userId, $ucsId)) > 0;
  }

  /**
   * Remove a relearned kanji from the selection.
   * 
   * @return boolean
   */
  public static function clearKanji($userId, $ucsId)
  {
    assert('(int)$ucsId > 0x3000');

    return self::getInstance()->delete('userid = ? AND ucs_id = ?', array($userId, $ucsId));
  }

  /**
   * Clear the relearned kanji list for this user.
   * 
   * @return boolean
   */
  public static function clearAll($userId)
  {
    $user = sfContext::getInstance()->getUser();
    $user->getAttributeHolder()->remove(rtkUser::IS_RESTUDY_SESSION);

    return self::getInstance()->delete('userid = ?', $userId);
  }
}
