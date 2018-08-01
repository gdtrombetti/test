<?php

class Content
{
    private $logo;
    private $type;

    public function __construct($logo, $type) {
        $this->logo = $logo;
        $this->type = $type;
    }
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo (string $logo) {
        $this->logo = $logo;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type) {
        $this->type = $type;
    }
}