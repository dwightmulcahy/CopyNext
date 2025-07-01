<?php
if (!defined('DOKU_INC')) die();

class action_plugin_copynext extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_copynext');
    }

    public function handle_copynext(Doku_Event $event, $param) {
        global $ID;

        if (!isset($_GET['copynext'])) return;

        // Match ID pattern: journal:day:YYYY:MM:DD
        if (!preg_match('/^journal:day:(\d{4}):(\d{2}):(\d{2})$/', $ID, $matches)) {
            msg("Current page ID does not match expected format journal:day:YYYY:MM:DD", -1);
            return;
        }

        list(, $year, $month, $day) = $matches;
        $currentDate = "$year-$month-$day";

        // Add 7 days
        $newDate = date('Y-m-d', strtotime("$currentDate +7 days"));
        $formattedDate = date('D m/d', strtotime($newDate)); // e.g., Mon 07/07
        list($newYear, $newMonth, $newDay) = explode('-', $newDate);
        $newID = "journal:day:$newYear:$newMonth:$newDay";

        // Load current page source
        $currentPagePath = wikiFN($ID);
        if (!file_exists($currentPagePath)) {
            msg("Source page does not exist.", -1);
            return;
        }

        $lines = file($currentPagePath);
        $filtered = [];
        $titleReplaced = false;

        foreach ($lines as $line) {
            // Remove lines with any <todo ... #dwight:...> regardless of position or list prefix
            if (preg_match('/<todo[^>]*#dwight:[^>]*>/', $line)) {
                continue;
            }

            // Replace the first title line matching "====== ... ======"
            if (!$titleReplaced && preg_match('/^====== .*? ======\s*$/', $line)) {
                $line = "====== $formattedDate ======
";
                $titleReplaced = true;
            }

            $filtered[] = $line;
        }

        $newContent = implode('', $filtered);

        // Write to new page if it doesn't exist
        $newPagePath = wikiFN($newID);
        if (file_exists($newPagePath)) {
            msg("Target page already exists: $newID", -1);
            return;
        }

        saveWikiText($newID, $newContent, "Copied from $ID (+7 days, filtered and retitled)");

        // Redirect to the new page in edit mode
        send_redirect(wl($newID, ['do' => 'edit'], true, '&'));
    }
}
