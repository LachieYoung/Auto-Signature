<?php
    class AutoSignature {

        /**
         * @return json - A json encoded array of template file names found in our /templates directory
         */

        public function ajax_get_templates()
        {
            $templates = array_diff(scandir('templates'), array('.','..'));
            $templates = array_values($templates);
            
            $template_arr = [];
            foreach($templates as $template) {
                $template_arr[] = ucwords(basename($template, '.html'));
            }

            print_r(json_encode($template_arr));
        }


        /**
         * @param $template - The template that the user has chosen from the dynamically populated dropdown
         * @return json - A json encoded array of tags found in the template file
         */

        public function ajax_get_template_tags($template)
        {
            $file = file_get_contents('templates/' . $template . '.html');
            // This will however produce duplicates of things like {email} because it is also used in the mailto: attrib
            // We will need to clear out duplicates when we create the form however we will need to still replace these when we insert our values
            $file = preg_match_all('/\{\w+\}/', $file, $matches, PREG_PATTERN_ORDER);
            
            $tags = [];
            foreach($matches as $match) {
                $tags[] = str_replace(['{', '}'], '', $match);
            }

            print_r(json_encode(array_unique($tags[0])));
        }


        /**
         * @param $template - The template that the user has chosen from the dynamically populated dropdown
         * @param $form - Form data submitted by the user
         * @return html - The populated signature to be displayed to the user
         */
    
        public function ajax_preview_signature($template, $form)
        {
            // Read our template file
            $file = file_get_contents('templates/' . $template . '.html');
            
            // Find our tags and push them to a $matches array
            $tags = preg_match_all('/\{\w+\}/', $file, $matches, PREG_PATTERN_ORDER);

            // Loop through our form array data to only grab the values we entered
            $user_details = [];
            foreach($form as $formdata) {
                $user_details[] = $formdata['value'];
            }

            // Replace tags with form data submitted by user
            $signature = str_replace($matches[0], $user_details, $file);

            echo $signature;
        }

    }
?>