package com.swiftype.codegen;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import org.openapitools.codegen.CodegenConfig;
import org.openapitools.codegen.CodegenOperation;
import org.openapitools.codegen.CodegenType;
import org.openapitools.codegen.SupportingFile;
import org.openapitools.codegen.languages.PhpClientCodegen;
import org.openapitools.codegen.utils.ModelUtils;

import io.swagger.v3.oas.models.Operation;
import io.swagger.v3.oas.models.media.Schema;

public class SwiftypePhpGenerator extends PhpClientCodegen implements CodegenConfig {

  public final static String GENERATOR_NAME = "swiftype-php";

  public SwiftypePhpGenerator() {
    super();

    this.setTemplateDir(SwiftypePhpGenerator.GENERATOR_NAME);
    this.setSrcBasePath("");
    this.embeddedTemplateDir = this.templateDir();

    this.apiDirName = "Endpoint";
    setApiPackage(getInvokerPackage() + "\\" + apiDirName);
    this.setParameterNamingConvention("camelCase");
  }

  @Override
  public void processOpts() {
    super.processOpts();
    this.resetTemplateFiles();

    supportingFiles.add(new SupportingFile("Client.mustache", "", "Client.php"));
  }

  @Override
  public CodegenType getTag() {
    return CodegenType.CLIENT;
  }

  @Override
  public String getName() {
    return SwiftypePhpGenerator.GENERATOR_NAME;
  }

  @Override
  public String toApiName(String name) {
    return initialCaps(name);
  }

  @Override
  @SuppressWarnings("static-method")
  public void addOperationToGroup(String tag, String resourcePath,
      Operation operation, CodegenOperation co,
      Map<String, List<CodegenOperation>> operations) {
    String uniqueName = co.operationId;
    List<CodegenOperation> opList = new ArrayList<CodegenOperation>();
    co.operationIdLowerCase = uniqueName.toLowerCase(Locale.ROOT);
    co.operationIdCamelCase = org.openapitools.codegen.utils.StringUtils.camelize(uniqueName);
    co.operationIdSnakeCase = org.openapitools.codegen.utils.StringUtils.underscore(uniqueName);

    opList.add(co);
    operations.put(uniqueName, opList);
  }

  @Override
  public String getTypeDeclaration(Schema p) {
    if (ModelUtils.isObjectSchema(p)) {
      return "array";
    }

    return super.getTypeDeclaration(p);
  }

  private void resetTemplateFiles() {
    this.supportingFiles.clear();
    this.apiTemplateFiles.clear();
    this.apiTestTemplateFiles.clear();
    this.apiDocTemplateFiles.clear();
    this.modelTemplateFiles.clear();
    this.modelTestTemplateFiles.clear();
    this.modelDocTemplateFiles.clear();

    apiTemplateFiles.put("api.mustache", ".php");
  }
}