<?php

class JobsView extends Jobs {

    public function viewMyJobs($autor) {
      return $this->viewJobs($autor);
    }

}
