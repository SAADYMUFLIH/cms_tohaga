<title>UMKM Tohaga | <?php echo $page_title ?> </title>
<div class="app-main__outer">
  <!-- Opener For Content, Div Tag Close on Footer -->
  <div class="app-main__inner">
    <div id="web_content">
      <?php 
      require_once('breadcrumb.php');

      $uid = array(
        'sess_id' => $this->input->get('sessid')
      );
      $this->session->set_userdata($uid);
      if (!defined('BASEPATH')) exit('No direct script access allowed');

      if ($webcontent) {
        $this->load->view($webcontent);
      }

      ?>

    </div>
  </div> <!-- id="web_content" -->