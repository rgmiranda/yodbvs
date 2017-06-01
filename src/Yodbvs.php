<?php

namespace yodbvs;


class Yodbvs {
    protected $conn;
    protected $dataFile;
    protected $miscDir;
    protected $revisionsDir;

    public function __construct () {
        $config = Config::getInstance();
        $this->conn = db\adapters\Adapter::createInstance($config->get('db'));
        $this->miscDir = $config->get('misc.dir');
        if (is_dir($this->miscDir)) {
            throw new RuntimeException("No se ha encontrado el directorio '{$this->miscDir}'");
        }
        if (is_writable($this->miscDir)) {
            throw new RuntimeException("El directorio '{$this->miscDir}' no es modificable");
        }
        $this->dataFile = $config->get('misc.datafile');
        $this->revisionsDir = $config->get('revisions.dir');
        if (is_dir($this->revisionsDir)) {
            throw new RuntimeException('Directorio de revisiones no encontrado');
        }
    }

    public function to($toRev) {
        $logger = Log::getInstance();
        try {
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
                $fileData = file_get_contents($this->dataFile);
                $fromRev = intval($fileData);
            } else {
                $fromRev = 1;
            }
            if ($fromRev < 1) {
                throw new RuntimeException('No se ha encontrado un valor válido ' .
                    'de la revisión actual');
            }
            $updateDir = $fromRev < $toRev ? 'update' : 'rollback';
            $revs = range($fromRev, $toRev, $fromRev < $toRev ? 1 : -1);
            $logger->log('info', "Ejecutando desde la revisión {$fromRev} a {$toRev}");
            foreach ($revs as $rev) {
                $revDir = implode(DIRECTORY_SEPARATOR, array ($this->revisionsDir, $rev, $updateDir));
                if (!is_dir($revDir)) {
                    throw new RuntimeException("No se ha encontrado la revisión {$rev}");
                }
                if (!is_readable($revDir)) {
                    throw new RuntimeException("El directorio {$revDir} no es accesible");
                }
                $revisionFiles = scandir($revDir);
                $logger->log('info', "Ejecutando la revisión {$rev}");
                foreach ($revisionFiles as $revFile) {
                    if (strlen($revFile) - 4 !== strrpos($revFile, '.sql')) {
                        // No es un archivo SQL.
                        continue;
                    }
                    $revFile = implode(DIRECTORY_SEPARATOR, array ($revDir, $revFile));
                    if (!is_readable($revFile)) {
                        throw new RuntimeException("El archivo {$revFile} no es accesible");
                    }
                    $logger->log('info', "Ejecutando el archivo '{$revFile}'");
                    $this->conn->query(file_get_contents($revFile));
                }
            }
            file_put_contents($this->dataFile, $toRev);
        } catch (InvalidArgumentException $ex) {
            $logger->log('error', $ex->getMessage(), array (
                'trace' => $ex->getTraceAsString()
            ));
        } catch (RuntimeException $ex) {
            $logger->log('critical', $ex->getMessage(), array (
                'trace' => $ex->getTraceAsString()
            ));
        } catch (Exception $ex) {
            $logger->log('critical', $ex->getMessage(), array (
                'trace' => $ex->getTraceAsString()
            ));
        }
    }
}