<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\FiredbirdPlatform;
use Doctrine\DBAL\Schema\FirebirdSchemaManager;

/**
 * Abstract base implementation of the {@link Doctrine\DBAL\Driver} interface for the PDO Firebird driver.
 *
 * @author Daniel Falkner <falkner@outlook.com>
 * @link   www.doctrine-project.org
 * @since  2.5
 */
abstract class AbstractFirebirdDriver implements Driver, ExceptionConverterDriver
{
    /**
     * {@inheritdoc}
     */
    public function convertException($message, DriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '23000':
                return new Exception\ConstraintViolationException($message, $exception);

            case '42000':
            case '42702':
            case '42725':
            case '42818':
            case '42S01':
            case '42S02':
            case '42S11':
            case '42S12':
            case '42S21':
            case '42S22':
                return new Exception\SyntaxErrorException($message, $exception);

            case '42S02':
                return new Exception\TableNotFoundException($message, $exception);

            case '42S01':
                return new Exception\TableExistsException($message, $exception);

            case '08001':
            case '08002':
            case '08003':
            case '08006':
            case '08007':
                return new Exception\ConnectionException($message, $exception);

            case '08004':
                return new Exception\ServerException($message, $exception);
        }

        return new Exception\DriverException($message, $exception);
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        $params = $conn->getParams();

        return $params['dbname'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new FiredbirdPlatform();
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        return new FirebirdSchemaManager($conn);
    }

}
