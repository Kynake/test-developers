CREATE TABLE documents(
   id INT PRIMARY KEY       NOT NULL,
   name           TEXT      NOT NULL,
   content        TEXT,
   timestamp      TIMESTAMP NOT NULL
);

CREATE TABLE signatures(
  id INT PRIMARY KEY       NOT NULL,
  name           TEXT      NOT NULL,
  issuer         TEXT      NOT NULL,
  ordering       INT       NOT NULL,
  timestamp      TIMESTAMP NOT NULL,
  id_document    INT REFERENCES documents(ID)
);

INSERT INTO documents VALUES (
  '1', 'Documento de Teste', 'Lorem Ipsum', CURRENT_TIMESTAMP
);

INSERT INTO documents VALUES (
  '2', 'Teste2', 'I Mendeley Mendeley', CURRENT_TIMESTAMP
);


INSERT INTO signatures VALUES (
  '1', 'Fulano da Silva', 'Empresa Certificadora 1', '1', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures VALUES (
  '2', 'Beltrano da Silva', 'Empresa Certificadora 2', '2', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures VALUES (
  '3', 'Ciclano da Silva', 'Empresa Certificadora 3', '1', CURRENT_TIMESTAMP, '2'
);

INSERT INTO signatures VALUES (
  '4', 'Deltrano da Silva', 'Empresa Certificadora 4', '2', CURRENT_TIMESTAMP, '2'
);
