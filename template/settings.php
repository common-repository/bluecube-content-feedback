<div class="wrap">

  <h1>BlueCube Content Feedback Settings</h1>

  <?php
  $this->showMessages();
  ?>

  <form method="post" action="options-general.php?page=bluecube-content-feedback" enctype="multipart/form-data">

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Name:</th>
        <td>
          <input type="text" name="full_name">
        </td>
      </tr>
    </table>

    <?php
    submit_button('Upload');
    ?>

  </form>
</div>
