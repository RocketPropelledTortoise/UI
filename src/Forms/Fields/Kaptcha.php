<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Captcha form field
     *
     * @author Stéphane Goetz
     */

/**
 * A Math Captcha Library that displays a number captcha similar to the drupal captcha.
 *
 * INSTRUCTIONS
 *
 * Place this class into you application/library directory and name it kaptcha.php
 *
 * create MY_Form_validation.php, add this code and save it in the application/libraries folder.
 *
 *   public function kaptcha($value) {
 *       $answer = $this->CI->input->post('kaptcha_answer');
 *       if ($this->CI->kaptcha->validate_kaptcha($value, $answer)) {
 *           return true;
 *       }
 *       return false;
 *   }
 *
 *
 * Add this to your controller form validation rules
 * $this->form_validation->set_rules('kaptcha', 'Math Question', 'required|is_numeric|kaptcha');
 *
 * You can either add this to your form using a single line <?php $this->kaptcha->display_kaptcha(); ?>
 * or call it as an array <?php $kaptcha = $this->kaptcha->create_kaptcha() ;?> which  will give you three items
 * First Number, Second Number and an encrypted answer which needs to be put in a hidden  input value with the
 * name and id set to kaptcha_answer.
 *
 * The CSS is you bit!
 *
 */
class Kaptcha extends Field
{
    protected function getDefaults()
    {
        return parent::getDefaults() + [
            'kaptcha' => array(
                'tip' => t(
                    'Resolvez ce calcul et entrez le resultat. Par exemple 1+3 = 4.' .
                    'Il s\'agit de définir que vous êtes bien humain et pour éviter les spams'
                ),
                'title' => 'Calcul : !first + !second =',
                'hidden_field' => 'kaptcha_answer'
            ),
        ];
    }

    protected $kaptcha;

    public function __construct($name, $data = array())
    {
        parent::__construct($name, $data);

        $this->kaptcha = array(
            'first' => rand(1, 10),
            'second' => rand(1, 10),
        );

        $this->kaptcha['answer'] = \Crypt::encrypt($this->kaptcha['first'] + $this->kaptcha['second']);

        $this->params['tip'] = $this->params['kaptcha']['tip'];
        $this->params['title'] = t(
            $this->params['kaptcha']['title'],
            array('!first' => $this->kaptcha['first'], '!second' => $this->kaptcha['second'])
        );
    }

    /**
     * Render the inner field
     */
    protected function renderInner()
    {
        parent::renderInner();

        $this->result .= "<input type=hidden name='{$this->params['kaptcha']['hidden_field']}' value='{$this->kaptcha['answer']}' />";
    }

    /**
     * Function that validates the user answer against the encrypted answer
     * @param  string $kaptcha
     * @param  string $answer
     * @return boolean
     */
    public static function validate_kaptcha($kaptcha = null, $answer = null)
    {
        if ($kaptcha && $answer) {
            $value = \Crypt::decrypt($answer);
            if ($kaptcha == $value) {
                return true;
            }
        }

        return false;
    }
}
