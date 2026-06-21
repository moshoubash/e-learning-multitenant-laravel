<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.admin')]
class Logs extends Component
{
    public $showDeleteModal = false;
    public $showViewModal = false;

    public $deletingFile = null;
    public $viewingFile = null;
    public $logContent = '';

    public function openViewModal($fileName)
    {
        $path = base_path('storage/logs/' . basename($fileName));

        if (! file_exists($path)) {
            Toaster::error(__('messages.Log file not found.'));
            return;
        }

        $this->viewingFile = $fileName;
        $this->logContent = $this->getLastLines($path, 500);
        $this->showViewModal = true;
    }

    public function openDeleteModal($fileName)
    {
        $this->deletingFile = $fileName;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->showViewModal = false;
        $this->deletingFile = null;
        $this->viewingFile = null;
        $this->logContent = '';
    }

    public function delete()
    {
        if (! $this->deletingFile) {
            return;
        }

        $path = base_path('storage/logs/' . basename($this->deletingFile));

        if (! file_exists($path)) {
            Toaster::error(__('messages.Log file not found.'));
            $this->closeModal();
            return;
        }

        unlink($path);
        $this->closeModal();
        Toaster::success(__('messages.Log file deleted successfully!'));
    }

    public function render()
    {
        $logFiles = $this->getLogFiles();

        return view('livewire.admin.logs', [
            'logFiles' => $logFiles,
        ]);
    }

    protected function getLogFiles(): array
    {
        $logPath = base_path('storage/logs');
        $files = glob($logPath . '/*.log');
        $logFiles = [];

        foreach ($files as $path) {
            $name = basename($path);
            if ($name === '.gitignore') {
                continue;
            }
            $logFiles[] = [
                'name' => $name,
                'size' => filesize($path),
                'size_formatted' => $this->formatBytes(filesize($path)),
                'last_modified' => filemtime($path),
                'last_modified_formatted' => date('Y-m-d H:i:s', filemtime($path)),
                'lines' => $this->countLines($path),
            ];
        }

        usort($logFiles, fn($a, $b) => $b['last_modified'] - $a['last_modified']);

        return $logFiles;
    }

    protected function getLastLines($path, $lines): string
    {
        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::DROP_NEW_LINE);
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $startLine = max(0, $totalLines - $lines);
        $content = '';

        foreach (new \LimitIterator($file, $startLine, $lines) as $line) {
            $content .= $line . "\n";
        }

        return $content;
    }

    protected function countLines($path): int
    {
        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::DROP_NEW_LINE);
        $file->seek(PHP_INT_MAX);
        return $file->key();
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
