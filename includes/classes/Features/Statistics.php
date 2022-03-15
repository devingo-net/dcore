<?php


namespace DCore\Features;

/**
 * Class Statistics
 *
 * @package DCore
 */
class Statistics
{
    const recordsOptionName = 'dc_statistics';
    const countOptionName = 'dc_statistics_count';

    public static function init()
    {
        $class = new self();
        $class->addRecord();
    }

    /**
     * get records count
     *
     * @param int $postID
     * @return int
     */
    public static function getRecordsCount(int $postID): int
    {
        $records = get_post_meta($postID, self::countOptionName, true);
        if (empty($records) || !is_numeric($records)) {
            return 0;
        }

        return $records;
    }

    /**
     * get all recorded users
     *
     * @param int $postID
     * @return array
     */
    public function getRecords(int $postID): array
    {
        $records = get_post_meta($postID, self::recordsOptionName, true);
        if (empty($records) || !is_array($records)) {
            $records = [];
        }

        return $records;
    }

    /**
     * add new record
     */
    public function addRecord(string $recordKey = ''): void
    {
        if (!is_singular()) {
            return;
        }

        global $post;
        if (!isset($post->ID)) {
            return;
        }

        $records = $this->getRecords($post->ID);

        $recordType = 'ip';

        if (empty($recordKey) && is_user_logged_in()) {
            $recordKey = get_current_user_id();
            $recordType = 'user';
        }

        if (empty($recordKey)) {
            $recordKey = dcGetUserIP();
        }

        if (!isset($records[$recordKey])) {
            $records[$recordKey] = [
                'type' => $recordType,
                'key' => $recordKey,
                'time' => time()
            ];
        }

        update_post_meta($post->ID, self::recordsOptionName, $records);
        update_post_meta($post->ID, self::countOptionName, count($records));
    }
}