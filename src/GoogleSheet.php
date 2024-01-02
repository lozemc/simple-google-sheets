<?php

namespace Lozemc;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheet
{
    private Sheets $service;
    private string $table_id;
    private string $sheet_name;

    public function __construct(string $table_id, string $account, string $sheet = '')
    {
        $this->validate($table_id, $account);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $account);

        $this->table_id = $table_id;
        $this->sheet_name = $sheet;

        $this->set_service();
    }

    public function set_sheet(string $sheet_name): GoogleSheet
    {
        $this->sheet_name = $sheet_name;
        return $this;
    }

    public function get_rows($range = '')
    {
        if (empty($this->sheet_name)) {
            throw new \RuntimeException('Select sheet (Method setSheet(\'sheet_name\'))');
        }

        $range = !empty($range) ? '!' . $range : $range;

        return $this->service->spreadsheets_values->get(
            $this->table_id,
            $this->sheet_name . $range
        )['values'] ?? [];
    }

    public function update(array $values, string $range = ''): void
    {
        $this->save('update', $values, $range);
    }

    public function append(array $values, string $range = ''): void
    {
        $this->save('append', $values, $range);
    }

    private function set_service(): void
    {
        $client = new Client();

        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->useApplicationDefaultCredentials();

        $this->service = new Sheets($client);
    }

    private function save(string $action, array $values, string $range = ''): void
    {
        $range = !empty($range) ? '!' . $range : '';

        $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);

        $this->service->spreadsheets_values->$action(
            $this->table_id,
            $this->sheet_name . $range,
            $body,
            ['valueInputOption' => 'RAW']
        );
    }

    private function validate($table_id, $account): void
    {
        if (empty(trim($table_id))) {
            throw new \InvalidArgumentException('Table name can\'t be empty');
        }

        if (!file_exists($account)) {
            throw new \InvalidArgumentException("File \"$account\" does not exist");
        }
    }
}
