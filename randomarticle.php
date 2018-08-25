<?php
/**
 * @package   Randomarticle
 * @type      Plugin (System)
 * @version   1.0.0
 * @author    Simon Champion
 * @copyright (C) 2018 Simon Champion
 * @license   GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
**/

defined('_JEXEC') or die;

class plgSystemRandomarticle extends JPlugin
{
    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        if ($app->isAdmin())
        {
            return;
        }

        $url = JUri::getInstance();
        $catmatcher = '(?<category>[-_a-zA-Z0-1]+)';
        $parampattern = $this->params->get('url_pattern', '[category]/random');
        $pattern = str_replace('[category]', $catmatcher, $parampattern);
        $match = preg_match('~'.$pattern.'~', $url, $matches);

        if ($match && isset($matches['category']) && $matches['category']) {
            $this->redirect($matches['category']);
        }
    }

    private function redirect($catName) {
        $app = JFactory::getApplication();
        $app->redirect($this->newUrl($catName));
    }

    private function newUrl($catName) {
        list($articleId, $catId) = $this->getRandomArticle($catName);

        JLoader::register('ContentHelperRoute', JPATH_ROOT . '/components/com_content/helpers/route.php');
        return JRoute::_(ContentHelperRoute::getArticleRoute($articleId, $catId));
    }

    private function getRandomArticle($catName) {
        //@todo: cache $catId and $articleIds so we can skip this bit.
        $catId = $this->loadCatId($catName);
        if (!$catId) {
            //throw new Exception(JText::_('PLG_RANDOMARTICLE_CAT_NOT_FOUND'), 404);
            throw new Exception('Category not found', 404);
        }
        $articleIds = $this->loadArticleIdList($catId);
        if (!$articleIds or !count($articleIds)) {
            //throw new Exception(JText::_('PLG_RANDOMARTICLE_CAT_EMPTY'), 404);
            throw new Exception('Category is empty', 404);
        }

        shuffle($articleIds);
        return array(array_pop($articleIds), $catId);
    }

    private function loadCatId($catName) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['id']);
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('alias') . ' = '. $db->quote($catName));

        $db->setQuery($query);
        return $db->loadResult();
    }

    private function loadArticleIdList($catId) {
        $query = "SELECT a.id FROM #__content a WHERE a.catid = $catId AND a.state = '1' ";

        $language = JFactory::getLanguage();
        if($language->getTag()){
            $query .= "AND a.language IN('*','".$language->getTag()."') ";
        }

        $query .= "AND (a.publish_up <= '".date('Y-m-d H:i:s')."' OR a.publish_up = '0000-00-00 00:00:00') ".
                "AND (a.publish_down >= '".date('Y-m-d H:i:s')."' OR a.publish_down = '0000-00-00 00:00:00') ";

        $db = JFactory::getDBO();
        $db->setQuery($query);
        return $db->loadColumn();
    }
}

