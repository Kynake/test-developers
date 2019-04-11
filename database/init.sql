CREATE TABLE documents(
   id SERIAL PRIMARY KEY      NOT NULL,
   name           TEXT UNIQUE NOT NULL,
   content        TEXT,
   timestamp      TIMESTAMP   NOT NULL
);

CREATE TABLE signatures(
  id SERIAL PRIMARY KEY      NOT NULL,
  name           TEXT UNIQUE NOT NULL,
  issuer         TEXT        NOT NULL,
  ordering       INT         NOT NULL,
  timestamp      TIMESTAMP   NOT NULL,
  id_document    INT NOT NULL REFERENCES documents(ID)
);

INSERT INTO documents (name, content, timestamp) VALUES (
  'Documento de Teste', 'Lorem Ipsum', CURRENT_TIMESTAMP
);

INSERT INTO documents (name, content, timestamp) VALUES (
  'Teste2', 'I Mendeley Mendeley', CURRENT_TIMESTAMP
);


INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Fulano da Silva', 'Empresa Certificadora 1', '1', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Beltrano da Silva', 'Empresa Certificadora 2', '2', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
 'Ciclano da Silva', 'Empresa Certificadora 3', '1', CURRENT_TIMESTAMP, '2'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Deltrano da Silva', 'Empresa Certificadora 4', '2', CURRENT_TIMESTAMP, '2'
);
