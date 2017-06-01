<?php

namespace yodbvs;

class Yodbvs {
    protected $conn;
    protected $dataDir;
    protected $dataFile;
    protected $revisionsDir;

    public function __construct ($config) {
        $s = DIRECTORY_SEPARATOR;
        $this->conn = db\adapters\Adapter::createInstance($config['db']);
        $this->dataDir = realpath(__DIR__ . $s . implode($s, array('..', '..', 'data')));
        $this->dataFile = implode($s, array ($this->dataDir, 'misc', 'data.dat'));
        $this->revisionsDir = implode($s, array ($this->dataDir, 'revisions'));
        if (is_dir($this->revisionsDir)) {
            throw new RuntimeException('Directorio de revisiones no encontrado');
        }
    }

    public function to($toRev) {
        $toRev = filter_var($toRev, FILTER_VALIDATE_INT, array (
            'options' => array (
                'min_range' => 1
            )
        ));
        if (empty($toRev)) {
            throw new InvalidArgumentException('Se esperaba un número entero ' .
                'positivo en $rev');
        }
        if (file_exists($this->dataFile) && is_readable($this->dataFile)) {
            $fileData = unserialize(file_get_contents($this->dataFile));
            $fromRev = intval($fileData['rev']);
        } else {
            $fromRev = 1;
        }
        if ($fromRev < 1) {
            throw new RuntimeException('No se ha encontrado un valor válido ' .
                'de la revisión actual');
        }
        $updateDir = $fromRev < $toRev ? 'update' : 'rollback';
        $revs = range($fromRev, $toRev, $fromRev < $toRev ? 1 : -1);
        foreach ($revs as $rev) {

        }
    }
}