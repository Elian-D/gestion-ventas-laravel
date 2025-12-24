<?php

namespace Database\Seeders\ConfigurationSeeders;

use Illuminate\Database\Seeder;
use App\Models\Geo\Country;
use App\Models\Configuration\TaxIdentifierType;

class TaxIdentifierTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Afganistán
            'AFG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Albania
            'ALB' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NID',
                    'name' => 'Numri i Identitetit',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NIPT',
                    'name' => 'Numri i Identifikimit për Personin e Tatueshëm',
                ],
            ],

            // Alemania
            'DEU' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Steuer ID',
                    'name' => 'Steueridentifikationsnummer',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Ust-IdNr',
                    'name' => 'Umsatzsteur Identifikationnummer',
                ],
            ],

            // Andorra
            'AND' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NRT',
                    'name' => 'Número de Registre Tributari',
                ],
            ],

            // Angola
            'AGO' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Número de Identificação Fiscal',
                ],
            ],

            // Anguila
            'AIA' => [],

            // Antártida
            'ATA' => [],

            // Antigua y Barbuda
            'ATG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Arabia Saudita
            'SAU' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'VAT Registration Number',
                ],
            ],

            // Argelia
            'DZA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Argentina
            'ARG' => [
                [
                    'entity_type' => 'person',
                    'code' => 'DNI',
                    'name' => 'Documento Nacional de Identidad',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'CUIT',
                    'name' => 'Clave Única de Identificación Tributaria',
                ],
            ],

            // Armenia
            'ARM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Aruba
            'ABW' => [
                [
                    'entity_type' => 'both',
                    'code' => 'Persoonsnummer',
                    'name' => 'Persoonsnummer',
                ],
            ],

            // Australia
            'AUS' => [
                [
                    'entity_type' => 'person',
                    'code' => 'TFN',
                    'name' => 'Tax File Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ABN',
                    'name' => 'Australian Business Number',
                ],
            ],

            // Austria
            'AUT' => [
                [
                    'entity_type' => 'person',
                    'code' => 'VNR',
                    'name' => 'Versicherungsnummer',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'UID',
                    'name' => 'Umsatzsteuer-Identifikationsnummer',
                ],
            ],

            // Azerbaiyán
            'AZE' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PIN',
                    'name' => 'Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VÖEN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Bahamas
            'BHS' => [
                [
                    'entity_type' => 'company',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Bangladés
            'BGD' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Barbados
            'BRB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TAMIS ID',
                    'name' => 'Tax Administration Management Information System ID',
                ],
            ],

            // Baréin
            'BHR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'SSN',
                    'name' => 'Social Security Number',
                ],
            ],

            // Bélgica
            'BEL' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Rijksregisternummer',
                    'name' => 'Belgian National Register Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BTW',
                    'name' => 'Belgian Enterprise Number',
                ],
            ],

            // Belice
            'BLZ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Benín
            'BEN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'IFU',
                    'name' => 'Identifiant Fiscal Unique',
                ],
            ],

            // Bermudas
            'BMU' => [],

            // Bielorrusia
            'BLR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'UNP',
                    'name' => 'Payer\'s Account Number',
                ],
            ],

            // Birmania (Myanmar)
            'MMR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Bolivia
            'BOL' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CI',
                    'name' => 'Cédula de Identidad',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NIT',
                    'name' => 'Número de Identificación Tributaria',
                ],
            ],

            // Bonaire, San Eustaquio y Saba
            'BES' => [],

            // Bosnia y Herzegovina
            'BIH' => [
                [
                    'entity_type' => 'person',
                    'code' => 'JMBG',
                    'name' => 'Unique Master Citizen Number',
                ],
            ],

            // Botsuana
            'BWA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Brasil
            'BRA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CPF',
                    'name' => 'Cadastro de Pessoas Físicas',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'CNPJ',
                    'name' => 'Cadastro Nacional da Pessoa Jurídica',
                ],
            ],

            // Brunéi
            'BRN' => [],

            // Bulgaria
            'BGR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'EGN',
                    'name' => 'Edinen grazhdanski nomer',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'Идентификационен номер по ДДС',
                ],
            ],

            // Burkina Faso
            'BFA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'IFU',
                    'name' => 'Identifiant Fiscal Unique',
                ],
            ],

            // Burundi
            'BDI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Bután
            'BTN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TPN',
                    'name' => 'Tax Payer Number',
                ],
            ],

            // Cabo Verde
            'CPV' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Número de Identificação Fiscal',
                ],
            ],

            // Camboya
            'KHM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Camerún
            'CMR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIU',
                    'name' => 'Numéro d\'Identifiant Unique',
                ],
            ],

            // Canadá
            'CAN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'SIN',
                    'name' => 'Social Insurance Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BN',
                    'name' => 'Business Number',
                ],
            ],

            // Chad
            'TCD' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Chequia
            'CZE' => [
                [
                    'entity_type' => 'person',
                    'code' => 'RČ',
                    'name' => 'Rodné Císlo',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'DIČ',
                    'name' => 'Danové Identifikacni Cislo',
                ],
            ],

            // Chile
            'CHL' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUT',
                    'name' => 'Rol Único Tributario',
                ],
            ],

            // China
            'CHN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Social Number',
                    'name' => 'Resident Identity Card',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Business Number',
                    'name' => 'Unified Social Credit Code',
                ],
            ],

            // Chipre
            'CYP' => [
                [
                    'entity_type' => 'person',
                    'code' => 'ID',
                    'name' => 'Identity Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ΦΠΑ',
                    'name' => 'VAT Number',
                ],
            ],

            // Colombia
            'COL' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NIT',
                    'name' => 'Número De Identificación Tributaria',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'RUT',
                    'name' => 'Registro Unico Tributario',
                ],
            ],

            // Comoras
            'COM' => [],

            // Congo
            'COG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIU',
                    'name' => 'Numéro d\'Identification Unique',
                ],
            ],

            // Congo, R.D.
            'COD' => [
                [
                    'entity_type' => 'both',
                    'code' => 'Impôt',
                    'name' => 'Numéro Impôt',
                ],
            ],

            // Corea del Norte
            'PRK' => [],

            // Corea del Sur
            'KOR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'RRN',
                    'name' => 'Resident Registration Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BRN',
                    'name' => 'Business Registration Number',
                ],
            ],

            // Costa de Marfil
            'CIV' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NCC',
                    'name' => 'Numéro de Compte Contribuable',
                ],
            ],

            // Costa Rica
            'CRI' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CPF',
                    'name' => 'Cédula de Persona Física',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'CPJ',
                    'name' => 'Cédula de Persona Jurídica',
                ],
            ],

            // Croacia
            'HRV' => [
                [
                    'entity_type' => 'both',
                    'code' => 'OIB',
                    'name' => 'Osobni identifikacijski broj',
                ],
            ],

            // Cuba
            'CUB' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NI',
                    'name' => 'Número de Identidad',
                ],
            ],

            // Curazao
            'CUW' => [
                [
                    'entity_type' => 'both',
                    'code' => 'Crib-nummer',
                    'name' => 'Crib-nummer',
                ],
            ],

            // Dinamarca
            'DNK' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CPR',
                    'name' => 'Det Centrale Personregister',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'CVR',
                    'name' => 'Momsregistreringsnummer',
                ],
            ],

            // Dominica
            'DMA' => [],

            // Ecuador
            'ECU' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUC',
                    'name' => 'Registro Unico de Contribuyentes',
                ],
            ],

            // Egipto
            'EGY' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TRN',
                    'name' => 'Tax Registration Number',
                ],
            ],

            // El Salvador
            'SLV' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIT',
                    'name' => 'Número de Identificación Tributaria',
                ],
            ],

            // Emiratos Árabes Unidos
            'ARE' => [
                [
                    'entity_type' => 'company',
                    'code' => 'TRN',
                    'name' => 'Tax Registration Number',
                ],
            ],

            // Eritrea
            'ERI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Eslovaquia
            'SVK' => [
                [
                    'entity_type' => 'person',
                    'code' => 'RČ',
                    'name' => 'Rodné Císlo',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'IČ DPH',
                    'name' => 'Identifikačné číslo pre daň z pridanej hodnoty',
                ],
            ],

            // Eslovenia
            'SVN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'JMBG',
                    'name' => 'Unique Master Citizen Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'DDV',
                    'name' => 'Identifikacijska številka za DDV',
                ],
            ],

            // España
            'ESP' => [
                [
                    'entity_type' => 'person',
                    'code' => 'DNI/NIE',
                    'name' => 'Documento Nacional de Identidad',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NIF/CIF',
                    'name' => 'Número de Identificación Fiscal',
                ],
            ],

            // Estados Unidos
            'USA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'SSN',
                    'name' => 'Social Security Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'EIN',
                    'name' => 'Employer Identification Number',
                ],
            ],

            // Estonia
            'EST' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Isikukood',
                    'name' => 'Personal Id',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'KMKR',
                    'name' => 'Registrikood',
                ],
            ],

            // Etiopía
            'ETH' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Filipinas
            'PHL' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Finlandia
            'FIN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'HETU',
                    'name' => 'Henkilötunnus',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ALV',
                    'name' => 'Arvonlisaveronumero',
                ],
            ],

            // Fiyi
            'FJI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Francia
            'FRA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NIR',
                    'name' => 'Numéro d’inscription au répertoire',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'SIREN',
                    'name' => 'Système d’identification du répertoire des entreprises',
                ],
            ],

            // Gabón
            'GAB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Gambia
            'GMB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Georgia
            'GEO' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PIN',
                    'name' => 'Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ID',
                    'name' => 'Identification Number',
                ],
            ],

            // Ghana
            'GHA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Gibraltar
            'GIB' => [],

            // Granada
            'GRD' => [],

            // Grecia
            'GRC' => [
                [
                    'entity_type' => 'person',
                    'code' => 'AMKA',
                    'name' => 'Αριθμός Μητρώου Κοινωνικής Ασφάλισης',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'FPA',
                    'name' => 'VAT Number',
                ],
            ],

            // Groenlandia
            'GRL' => [],

            // Guadalupe
            'GLP' => [],

            // Guam
            'GUM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'SSN',
                    'name' => 'Social Security Number',
                ],
            ],

            // Guatemala
            'GTM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIT',
                    'name' => 'Número de Identificación Tributaria',
                ],
            ],

            // Guayana Francesa
            'GUF' => [],

            // Guernsey
            'GGY' => [],

            // Guinea
            'GIN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Guinea Ecuatorial
            'GNQ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Número de Identificación Fiscal',
                ],
            ],

            // Guinea-Bisáu
            'GNB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Número de Identificação Fiscal',
                ],
            ],

            // Guyana
            'GUY' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Haití
            'HTI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Honduras
            'HND' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RTN',
                    'name' => 'Registro Tributario Nacional',
                ],
            ],

            // Hong Kong
            'HKG' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Social Number',
                    'name' => 'HKID Number',
                ],
            ],

            // Hungría
            'HUN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Szemelyi Szam',
                    'name' => 'Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ANUM',
                    'name' => 'Kozossegi Adoszam',
                ],
            ],

            // India
            'IND' => [
                [
                    'entity_type' => 'both',
                    'code' => 'PAN',
                    'name' => 'Permanent Account Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'GSTIN',
                    'name' => 'Goods and Services Tax Identification Number',
                ],
            ],

            // Indonesia
            'IDN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NPWP',
                    'name' => 'Nomor Pokok Wajib Pajak',
                ],
            ],

            // Irak
            'IRQ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Irán
            'IRN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Irlanda
            'IRL' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PPS No',
                    'name' => 'Personal Public Service Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'Irish Tax Reference Number',
                ],
            ],

            // Isla Bouvet
            'BVT' => [],

            // Isla de Man
            'IMN' => [],

            // Isla de Navidad
            'CXR' => [],

            // Isla Norfolk
            'NFK' => [],

            // Islandia
            'ISL' => [
                [
                    'entity_type' => 'both',
                    'code' => 'Kennitala',
                    'name' => 'Icelandic Identification Number',
                ],
            ],

            // Islas Caimán
            'CYM' => [],

            // Islas Cocos
            'CCK' => [],

            // Islas Cook
            'COK' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RMD',
                    'name' => 'Revenue Management Division Number',
                ],
            ],

            // Islas Feroe
            'FRO' => [
                [
                    'entity_type' => 'person',
                    'code' => 'P-number',
                    'name' => 'Personal Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'V-number',
                    'name' => 'VAT Number',
                ],
            ],

            // Islas Georgia del Sur y Sandwich del Sur
            'SGS' => [],

            // Islas Heard y McDonald
            'HMD' => [],

            // Islas Malvinas
            'FLK' => [],

            // Islas Marianas del Norte
            'MNP' => [],

            // Islas Marshall
            'MHL' => [],

            // Islas Menores de EE. UU.
            'UMI' => [],

            // Islas Pitcairn
            'PCN' => [],

            // Islas Salomón
            'SLB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Islas Turcas y Caicos
            'TCA' => [],

            // Islas Vírgenes Británicas
            'VGB' => [],

            // Islas Vírgenes de los EE. UU.
            'VIR' => [],

            // Israel
            'ISR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'ID',
                    'name' => 'Identity Number',
                ],
            ],

            // Italia
            'ITA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Codice Fiscale',
                    'name' => 'Fiscal Code',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Partita IVA',
                    'name' => 'VAT Number',
                ],
            ],

            // Jamaica
            'JAM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TRN',
                    'name' => 'Tax Registration Number',
                ],
            ],

            // Japón
            'JPN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'My Number',
                    'name' => 'Individual Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Corporate Number',
                    'name' => 'hōjin bangō',
                ],
            ],

            // Jersey
            'JEY' => [],

            // Jordania
            'JOR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Kazajistán
            'KAZ' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PIN',
                    'name' => 'Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BIN',
                    'name' => 'Business Identification Number',
                ],
            ],

            // Kenia
            'KEN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'PIN',
                    'name' => 'Personal Identification Number',
                ],
            ],

            // Kirguistán
            'KGZ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'INN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Kiribati
            'KIR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Kosovo
            'XKX' => [
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'VAT Number',
                ],
            ],

            // Kuwait
            'KWT' => [],

            // Laos
            'LAO' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Lesoto
            'LSO' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Letonia
            'LVA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Personas kods',
                    'name' => 'Personal Code',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'PVN',
                    'name' => 'Pievienotās vērtības nodokļa',
                ],
            ],

            // Líbano
            'LBN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Liberia
            'LBR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Libia
            'LBY' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Liechtenstein
            'LIE' => [
                [
                    'entity_type' => 'company',
                    'code' => 'UID',
                    'name' => 'Unternehmens-Identifikationsnummer',
                ],
            ],

            // Lituania
            'LTU' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Asmens kodas',
                    'name' => 'Personal Code',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'PVM',
                    'name' => 'Pridėtinės vertės mokestis',
                ],
            ],

            // Luxemburgo
            'LUX' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PIC',
                    'name' => 'Personal identification code',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'TVA',
                    'name' => 'Taxe sur la valeur ajoutée',
                ],
            ],

            // Macao
            'MAC' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Macedonia del Norte
            'MKD' => [
                [
                    'entity_type' => 'company',
                    'code' => 'VAT Number',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Madagascar
            'MDG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Malasia
            'MYS' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Malaui
            'MWI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TPIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Maldivas
            'MDV' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Malí
            'MLI' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Malta
            'MLT' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Identity Card',
                    'name' => 'National Identity Card Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT Number',
                    'name' => 'Value Added Tax Number',
                ],
            ],

            // Marruecos
            'MAR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'IF',
                    'name' => 'Identifiant Fiscal',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'ICE',
                    'name' => 'Identifiant Commun de l\'Entreprise',
                ],
            ],

            // Martinica
            'MTQ' => [],

            // Mauricio
            'MUS' => [
                [
                    'entity_type' => 'person',
                    'code' => 'ID Number',
                    'name' => 'Mauritian National Identifier',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'TAN',
                    'name' => 'Tax Account Number',
                ],
            ],

            // Mauritania
            'MRT' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Mayotte
            'MYT' => [],

            // México
            'MEX' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RFC',
                    'name' => 'Registro Federal de Contribuyentes',
                ],
            ],

            // Micronesia
            'FSM' => [],

            // Moldavia
            'MDA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'IDNP',
                    'name' => 'Identification Number of Person',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'TVA',
                    'name' => 'Nr. de Inregistrare TVA',
                ],
            ],

            // Mónaco
            'MCO' => [
                [
                    'entity_type' => 'company',
                    'code' => 'VAT Number',
                    'name' => 'Value Added Tax Number',
                ],
            ],

            // Mongolia
            'MNG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TTN',
                    'name' => 'Taxpayer Registration Number',
                ],
            ],

            // Montenegro
            'MNE' => [
                [
                    'entity_type' => 'both',
                    'code' => 'PIB',
                    'name' => 'Poreski Identifikacioni Broj',
                ],
            ],

            // Montserrat
            'MSR' => [],

            // Mozambique
            'MOZ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NUIT',
                    'name' => 'Número Único de Identificação Tributária',
                ],
            ],

            // Namibia
            'NAM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Nauru
            'NRU' => [],

            // Nepal
            'NPL' => [
                [
                    'entity_type' => 'both',
                    'code' => 'PAN',
                    'name' => 'Permanent Account Number',
                ],
            ],

            // Nicaragua
            'NIC' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUC',
                    'name' => 'Registro Único de Contribuyentes',
                ],
            ],

            // Níger
            'NER' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Nigeria
            'NGA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Niue
            'NIU' => [],

            // Noruega
            'NOR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Fødselsnummer',
                    'name' => 'National Identity Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Orgnr',
                    'name' => 'Organisasjonsnummer',
                ],
            ],

            // Nueva Caledonia
            'NCL' => [],

            // Nueva Zelanda
            'NZL' => [
                [
                    'entity_type' => 'both',
                    'code' => 'IRD',
                    'name' => 'Inland Revenue Department Number',
                ],
            ],

            // Omán
            'OMN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Países Bajos
            'NLD' => [
                [
                    'entity_type' => 'person',
                    'code' => 'BSN',
                    'name' => 'Burgerservicenummer',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BTW',
                    'name' => 'Omzetbelastingnummer',
                ],
            ],

            // Pakistán
            'PAK' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CNIC',
                    'name' => 'Computerized National Identity Card',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NTN',
                    'name' => 'National Tax Number',
                ],
            ],

            // Palaos
            'PLW' => [],

            // Palestina
            'PSE' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Panamá
            'PAN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUC',
                    'name' => 'Registro Único de Contribuyentes',
                ],
            ],

            // Papúa Nueva Guinea
            'PNG' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Paraguay
            'PRY' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUC',
                    'name' => 'Registro Unico de Contribuyentes',
                ],
            ],

            // Perú
            'PER' => [
                [
                    'entity_type' => 'both', // Actualizado a both, ya que personas también tienen RUC
                    'code' => 'RUC',
                    'name' => 'Registro Único de Contribuyente',
                ],
            ],

            // Polinesia Francesa
            'PYF' => [
                [
                    'entity_type' => 'both',
                    'code' => 'Tahiti Number',
                    'name' => 'Numéro Tahiti',
                ],
            ],

            // Polonia
            'POL' => [
                [
                    'entity_type' => 'person',
                    'code' => 'PESEL',
                    'name' => 'Polish National Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NIP',
                    'name' => 'Numer Identyfikacji Podatkowej',
                ],
            ],

            // Portugal
            'PRT' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NIC',
                    'name' => 'Número de identificação civil',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'NIF',
                    'name' => 'Numero de Identificacao Fiscal',
                ],
            ],

            // Puerto Rico
            'PRI' => [
                [
                    'entity_type' => 'person',
                    'code' => 'SSN',
                    'name' => 'Social Security Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'EIN',
                    'name' => 'Employer Identification Number',
                ],
            ],

            // Qatar
            'QAT' => [
                [
                    'entity_type' => 'company',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Reino Unido (Gran Bretaña)
            'GBR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NINO',
                    'name' => 'National Insurance Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT Reg No',
                    'name' => 'Value Added Tax Registration Number',
                ],
            ],

            // República Centroafricana
            'CAF' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // República Dominicana
            'DOM' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Cédula',
                    'name' => 'Cédula de Identidad y Electoral',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'RNC',
                    'name' => 'Registro Nacional del Contribuyente',
                ],
            ],

            // Reunión
            'REU' => [],

            // Ruanda
            'RWA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Rumania
            'ROU' => [
                [
                    'entity_type' => 'person',
                    'code' => 'CNP',
                    'name' => 'Cod Numeric Personal',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'Cod fiscal TVA',
                ],
            ],

            // Rusia
            'RUS' => [
                [
                    'entity_type' => 'person',
                    'code' => 'INN',
                    'name' => 'Taxpayer Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT',
                    'name' => 'VAT Number',
                ],
            ],

            // Sahara Occidental
            'ESH' => [],

            // Samoa
            'WSM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Samoa Americana
            'ASM' => [],

            // San Bartolomé
            'BLM' => [],

            // San Cristóbal y Nieves
            'KNA' => [],

            // San Marino
            'SMR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'COE',
                    'name' => 'Codice operatore economico',
                ],
            ],

            // San Martín (Francia)
            'MAF' => [],

            // San Martín (Países Bajos)
            'SXM' => [],

            // San Pedro y Miquelón
            'SPM' => [],

            // San Vicente y las Granadinas
            'VCT' => [],

            // Santa Elena, Ascensión y Tristán de Acuña
            'SHN' => [],

            // Santa Lucía
            'LCA' => [],

            // Santo Tomé y Príncipe
            'STP' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Número de Identificação Fiscal',
                ],
            ],

            // Senegal
            'SEN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NINEA',
                    'name' => 'Numéro d\'Identification National des Entreprises et Associations',
                ],
            ],

            // Serbia
            'SRB' => [
                [
                    'entity_type' => 'person',
                    'code' => 'JMBG',
                    'name' => 'Unique Master Citizen Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'PIB',
                    'name' => 'Poreski Identifikacioni Broj',
                ],
            ],

            // Seychelles
            'SYC' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Sierra Leona
            'SLE' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Singapur
            'SGP' => [
                [
                    'entity_type' => 'person',
                    'code' => 'NRIC',
                    'name' => 'National Registration Identity Card',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'UEN',
                    'name' => 'Unique Entity Number',
                ],
            ],

            // Siria
            'SYR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Somalia
            'SOM' => [],

            // Sri Lanka
            'LKA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Suazilandia (Esuatini)
            'SWZ' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Sudáfrica
            'ZAF' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Social Number',
                    'name' => 'National Identity Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VAT Code',
                    'name' => 'Value Added Tax Code',
                ],
            ],

            // Sudán
            'SDN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Sudán del Sur
            'SSD' => [],

            // Suecia
            'SWE' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Personnummer',
                    'name' => 'Personal Identity Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'Orgnr',
                    'name' => 'Organisationsnummer',
                ],
            ],

            // Suiza
            'CHE' => [
                [
                    'entity_type' => 'person',
                    'code' => 'AHV',
                    'name' => 'Sozialversicherungsnummer',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'UID',
                    'name' => 'Unternehmens-Identifikationsnummer',
                ],
            ],

            // Surinam
            'SUR' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Svalbard y Jan Mayen
            'SJM' => [],

            // Tailandia
            'THA' => [
                [
                    'entity_type' => 'person',
                    'code' => 'Citizen Number',
                    'name' => 'Thailand Citizen Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Taiwán
            'TWN' => [
                [
                    'entity_type' => 'person',
                    'code' => 'ID',
                    'name' => 'National Identification Card',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'BAN',
                    'name' => 'Business Administration Number',
                ],
            ],

            // Tanzania
            'TZA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Tayikistán
            'TJK' => [
                [
                    'entity_type' => 'both',
                    'code' => 'INN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Territorios Australes Franceses
            'ATF' => [],

            // Territorio Británico del Océano Índico
            'IOT' => [],

            // Timor Oriental
            'TLS' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Togo
            'TGO' => [
                [
                    'entity_type' => 'both',
                    'code' => 'NIF',
                    'name' => 'Numéro d\'Identification Fiscale',
                ],
            ],

            // Tokelau
            'TKL' => [],

            // Tonga
            'TON' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Trinidad y Tobago
            'TTO' => [
                [
                    'entity_type' => 'company',
                    'code' => 'BIR Number',
                    'name' => 'Board of Inland Revenue Number',
                ],
            ],

            // Túnez
            'TUN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'MF',
                    'name' => 'Matricule Fiscal',
                ],
            ],

            // Turkmenistán
            'TKM' => [],

            // Turquía
            'TUR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'T.C. Kimlik No',
                    'name' => 'Turkish Personal Identification Number',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'VKN',
                    'name' => 'Vergi Kimlik Numarası',
                ],
            ],

            // Tuvalu
            'TUV' => [],

            // Ucrania
            'UKR' => [
                [
                    'entity_type' => 'person',
                    'code' => 'RNTRC',
                    'name' => 'Registration Number of Taxpayers\' Registration Card',
                ],
                [
                    'entity_type' => 'company',
                    'code' => 'EDRPOU',
                    'name' => 'National State Registry of Ukrainian Enterprises and Organizations',
                ],
            ],

            // Uganda
            'UGA' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Uruguay
            'URY' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RUT',
                    'name' => 'Registro Único de Tributos',
                ],
            ],

            // Uzbekistán
            'UZB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'STI (INN)',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Vanuatu
            'VUT' => [
                [
                    'entity_type' => 'both',
                    'code' => 'CT',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Vaticano
            'VAT' => [],

            // Venezuela
            'VEN' => [
                [
                    'entity_type' => 'both',
                    'code' => 'RIF',
                    'name' => 'Registro de Información Fiscal',
                ],
            ],

            // Vietnam
            'VNM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'MST',
                    'name' => 'Ma So Thue (Tax Code)',
                ],
            ],

            // Wallis y Futuna
            'WLF' => [],

            // Yemen
            'YEM' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TIN',
                    'name' => 'Tax Identification Number',
                ],
            ],

            // Zambia
            'ZMB' => [
                [
                    'entity_type' => 'both',
                    'code' => 'TPIN',
                    'name' => 'Taxpayer Identification Number',
                ],
            ],

            // Zimbabue
            'ZWE' => [
                [
                    'entity_type' => 'both',
                    'code' => 'BP Number',
                    'name' => 'Business Partner Number',
                ],
            ]
        ];

        foreach ($data as $iso3 => $identifiers) {
            $country = Country::where('iso3', $iso3)->first();

            if (!$country) {
                continue;
            }

            foreach ($identifiers as $identifier) {
                TaxIdentifierType::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'code' => $identifier['code'],
                    ],
                    array_merge($identifier, [
                        'country_id' => $country->id,
                    ])
                );
            }
        }
    }
}
