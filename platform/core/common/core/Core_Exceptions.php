<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Exceptions extends CI_Exceptions {

    protected $ci = null;

    /**
     * Initialize execption class
     *
     * @return    void
     */
    public function __construct() {

        parent::__construct();

        if (function_exists('get_instance') && class_exists('CI_Controller', false)) {
            $this->ci = get_instance();
        }
    }

    // --------------------------------------------------------------------

    /**
     * 404 Page Not Found Handler
     *
     * @param   string  the page
     * @param   bool    log error yes/no
     * @return  string
     */
    public function show_404($page = '', $log_error = TRUE) {

        $heading = '404 Page Not Found';
        $message = 'The page you requested was not found.';

        // By default we log this, but allow a dev to skip it
        if ($log_error) {
            // Removed by Ivan Tcholakov, 12-OCT-2013.
            //log_message('error', '404 Page Not Found --> '.$_SERVER['REQUEST_URI']);
            //
        }

        // Added by Ivan Tcholakov, 12-OCT-2013.
        global $RTR;

        if (is_object($this->ci) && is_object($this->ci) && !empty($RTR->routes['404_override'])) {
 
            $this->ci->load->set_module('error_404');
            Modules::run($RTR->routes['404_override'].'/index');
            set_status_header(404);
            echo $this->ci->output->get_output();
            exit(EXIT_UNKNOWN_FILE);
        }

        set_status_header(404);
        //

        echo $this->show_error($heading, $message, 'error_404', 404);
        exit(EXIT_UNKNOWN_FILE);
    }

    // --------------------------------------------------------------------

    /**
     * General Error Page
     *
     * Takes an error message as input (either as a string or an array)
     * and displays it using the specified template.
     *
     * @param   string              $heading        Page heading
     * @param   string|string[]     $message        Error message
     * @param   string              $template       Template name
     * @param   int                 $status_code    (default: 500)
     *
     * @return  string                              Error page output
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        if (is_cli())
        {
            $message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
            $template = 'cli'.DIRECTORY_SEPARATOR.$template;
        }
        else
        {
            set_status_header($status_code);
            $message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
            $template = 'html'.DIRECTORY_SEPARATOR.$template;
        }

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        ob_start();
        // Modified by Ivan Tcholakov, 10-OCT-2013.
        //include(VIEWPATH.'errors'.DIRECTORY_SEPARATOR.$template.'.php');
        if (file_exists(VIEWPATH.'errors'.DIRECTORY_SEPARATOR.$template.'.php'))
        {
            include VIEWPATH.'errors'.DIRECTORY_SEPARATOR.$template.'.php';
        }
        else
        {
            include COMMONPATH.'views'.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR.$template.'.php';
        }
        //
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    // --------------------------------------------------------------------

    /**
     * Native PHP error handler
     *
     * @param   int             $severity       Error level
     * @param   string          $message        Error message
     * @param   string          $filepath       File path
     * @param   int             $line           Line number
     * @return  string                          Error page output
     */
    public function show_php_error($severity, $message, $filepath, $line)
    {
        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

        // For safety reasons we don't show the full file path in non-CLI requests
        if ( ! is_cli())
        {
            $filepath = str_replace('\\', '/', $filepath);
            if (FALSE !== strpos($filepath, '/'))
            {
                $x = explode('/', $filepath);
                $filepath = $x[count($x)-2].'/'.end($x);
            }

            $template = 'html'.DIRECTORY_SEPARATOR.'error_php';
        }
        else
        {
            $template = 'cli'.DIRECTORY_SEPARATOR.'error_php';
        }

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        ob_start();
        // Modified by Ivan Tcholakov, 10-OCT-2013.
        //include(VIEWPATH.'errors/error_php.php');
        if (file_exists(VIEWPATH.'errors/error_php.php'))
        {
            include VIEWPATH.'errors'.DIRECTORY_SEPARATOR.$template.'.php';
        }
        else
        {
            include COMMONPATH.'views'.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR.$template.'.php';
        }
        //
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

}
